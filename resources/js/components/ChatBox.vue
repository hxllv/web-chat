<template>
    <div>
        <div id="container" class="container border" style="height: 70vh">
            <div
                class="w-100"
                v-for="message in messagesData"
                :key="message.id"
                :class="{
                    'msg message-right pl-5': idSender == message.sender_id,
                    'msg message-left pr-5': idSender != message.sender_id
                }"
            >
                <span
                    class="p-2 border text-white text-break"
                    style="max-width: 50%"
                    :class="{
                        'bg-primary': idSender == message.sender_id,
                        'bg-secondary': idSender != message.sender_id
                    }"
                >
                    <div v-if="message.message_type === 0">
                        {{ message.message }}
                    </div>
                    <div v-if="message.message_type === 1">
                        <audio
                            :src="`/storage/${message.audio}`"
                            controls
                        ></audio>
                    </div>
                    <div v-if="message.message_type === 2">
                        <img
                            :src="`/storage/${message.media}`"
                            alt=""
                            class="img-fluid rounded"
                        />
                    </div>
                </span>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2 mb-2">
            <button
                class="btn btn-secondary ml-2 mr-2"
                @click="messageType = 0"
            >
                Text Message
            </button>
            <button
                class="btn btn-secondary ml-2 mr-2"
                @click="messageType = 1"
            >
                Voice Message
            </button>
            <button
                class="btn btn-secondary ml-2 mr-2"
                @click="messageType = 2"
            >
                Image
            </button>
        </div>
        <form
            class="form-inline"
            @submit="sendMessage"
            method="post"
            enctype="multipart/form-data"
        >
            <div class="input-group w-100">
                <input
                    v-if="messageType === 0"
                    v-model="message"
                    class="form-control"
                    type="text"
                    name=""
                    id=""
                />
                <div
                    v-if="messageType === 1"
                    class="form-control d-flex align-items-center"
                    style="padding-right: 0;"
                >
                    <div style="margin-left: -2em">
                        <vue-record-audio @result="onResult" />
                    </div>
                    <button
                        class="btn btn-primary ml-2"
                        v-if="audio != null"
                        @click="playPreventDefault"
                        style="z-index: 3"
                    >
                        Play
                    </button>
                    <div
                        ref="progressBar"
                        class="d-flex progress-bar-custom"
                        style="z-index: 2"
                    ></div>
                </div>
                <div
                    v-if="messageType === 2"
                    class="form-control d-flex align-items-center"
                >
                    <input
                        class="form-control-file"
                        accept="image/png, image/jpeg, image/webp"
                        type="file"
                        name=""
                        id=""
                        @change="onFileChange"
                    />
                </div>
                <div class="input-group-append">
                    <input
                        class="form-control btn btn-light border"
                        type="submit"
                        value="Send Message"
                    />
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    mounted() {
        this.properBubbles();

        setTimeout(this.scrollToEnd, 100);

        setInterval(() => {
            this.checkForUpdate().then(response => {
                if (response.scrollToBottom) {
                    this.scrollToEnd();
                }

                if (response.updated) {
                    this.properBubbles();
                }
            });
        }, 1000);

        this.audioInstance.addEventListener(
            "durationchange",
            () => {
                const ai = this.audioInstance;
                if (ai.duration != Infinity) {
                    var duration = ai.duration;
                    this.audioInstance.remove();
                    console.log(duration);
                    this.audioDuration = duration;
                    this.calculatingDuration = false;
                }
            },
            false
        );
    },
    data() {
        return {
            messagesData: {},
            message: "",
            media: null,
            audio: null,
            audioBlob: null,
            audioInstance: new Audio(),
            audioDuration: 0,
            calculatingDuration: false,
            messageType: 0
        };
    },
    methods: {
        scrollToEnd() {
            const container = this.$el.querySelector("#container");
            container.scrollTop = container.scrollHeight;
        },
        checkForUpdate() {
            return new Promise(resolve => {
                axios
                    .get(`/chat/${this.idReceiver}?not-view=true`)
                    .then(response => {
                        const { data } = response;
                        const diff = data.length - this.messagesData.length;

                        if (diff > 0) {
                            const newMsgs = data.slice(-diff);

                            newMsgs.forEach(msg => {
                                this.messagesData.push(msg);
                            });

                            const {
                                scrollTop,
                                scrollHeight,
                                offsetHeight
                            } = this.$el.querySelector(".container");

                            if (
                                scrollHeight - scrollTop - offsetHeight / 1.5 <=
                                offsetHeight
                            )
                                resolve({
                                    updated: true,
                                    scrollToBottom: true
                                });

                            resolve({
                                updated: true,
                                scrollToBottom: false
                            });
                        }

                        resolve({ updated: false, scrollToBottom: false });
                    })
                    .catch(error => {
                        console.log(error);
                    });
            });
        },
        sendMessage(e) {
            e.preventDefault();

            const config = {
                headers: { "content-type": "multipart/form-data" }
            };

            let formData = new FormData();
            if (this.message != "") formData.append("message", this.message);
            else if (this.media != null) formData.append("media", this.media);
            else if (this.audio != null) {
                let audioFile = new File([this.audioBlob], "temp", {
                    type: "audio/mp3"
                });
                console.log(audioFile);
                formData.append("audio", audioFile);
            }
            formData.append("messageType", this.messageType);

            axios
                .post(`/chat/${this.idReceiver}`, formData, config)
                .then(response => {
                    this.checkForUpdate().then(response => {
                        this.scrollToEnd();
                        if (response.updated) {
                            this.properBubbles();
                        }
                    });
                    this.message = "";
                    this.media = "";
                })
                .catch(error => {
                    console.log(error);
                    this.message = "";
                    this.media = "";
                });
        },
        properBubbles() {
            const allMsgs = document.querySelectorAll(".msg");

            allMsgs.forEach((msg, index) => {
                if (!allMsgs[index + 1]) {
                    if (msg.classList.contains("message-right"))
                        return msg.classList.add("message-right-last");
                    msg.classList.add("message-left-last");
                    return;
                }

                if (msg.classList.contains("message-right"))
                    return allMsgs[index + 1].classList.contains("message-left")
                        ? msg.classList.add("message-right-last")
                        : msg.classList.remove("message-right-last");

                allMsgs[index + 1].classList.contains("message-right")
                    ? msg.classList.add("message-left-last")
                    : msg.classList.remove("message-left-last");
            });
        },
        onFileChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.media = files[0];
        },
        onResult(data) {
            this.audioBlob = data;
            this.audio = window.URL.createObjectURL(data);
        },
        playPreventDefault(e) {
            e.preventDefault();
            this.$refs.progressBar.style.setProperty(
                "--transition-duration",
                `${this.audioDuration}s`
            );

            this.audioInstance.play();
            this.$refs.progressBar.classList.add("playing");

            setTimeout(() => {
                this.$refs.progressBar.classList.remove("playing");
                this.$refs.progressBar.style.setProperty(
                    "--transition-duration",
                    `0s`
                );
            }, (this.audioDuration + 0.15) * 1000);
        }
    },
    props: ["messages", "idSender", "idReceiver"],
    watch: {
        messageType() {
            this.message = "";
            this.file = null;
        },
        audio() {
            this.audioInstance.src = this.audio;
            this.calculatingDuration = true;
            this.audioInstance.load();
            this.audioInstance.currentTime = 24 * 60 * 60; //fake big time
            this.audioInstance.volume = 0;
            this.audioInstance.play();
            this.audioInstance.volume = 1;
        }
    },
    created() {
        this.messagesData = JSON.parse(this.messages);
    }
};
</script>

<style scoped>
div.container {
    overflow-y: auto;
}

.message-left {
    display: flex;
    justify-content: flex-start;
}

.message-left-last > span {
    border-radius: 0 10px 10px 10px !important;
}

.message-right {
    display: flex;
    justify-content: flex-end;
}

.message-right-last > span {
    border-radius: 10px 0 10px 10px !important;
}

span.bg-primary {
    border-radius: 10px 0 0 10px;
}

span.bg-secondary {
    border-radius: 0 10px 10px 0;
}

.vue-audio-recorder {
    background-color: #3490dc;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.vue-audio-recorder:hover {
    background-color: #227dc7;
}

.progress-bar-custom {
    flex-grow: 1;
    height: 100%;
    position: relative;
    --transition-duration: 0s;
}

.progress-bar-custom::after {
    content: "";
    background-color: #6c757d;
    position: absolute;
    inset: 0;
    transform: scaleX(0);
    transform-origin: left;
    transition-property: transform;
    transition-timing-function: cubic-bezier(1, 1, 0, 0);
    transition-duration: var(--transition-duration);
}

.progress-bar-custom.playing::after {
    transform: scaleX(1);
}
</style>
