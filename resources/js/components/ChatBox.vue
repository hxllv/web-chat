<template>
    <div>
        <div class="container border" style="height: 75vh">
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
                    class="p-2 border text-white"
                    :class="{
                        'bg-primary': idSender == message.sender_id,
                        'bg-secondary': idSender != message.sender_id
                    }"
                    >{{ message.message }}
                </span>
            </div>
        </div>
        <form class="form-inline mt-2" @submit="sendMessage" method="post">
            <div class="input-group w-100">
                <input
                    v-model="message"
                    class="form-control"
                    type="text"
                    name=""
                    id=""
                />
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
        this.scrollToEnd();

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
    },
    data() {
        return {
            messagesData: {},
            message: ""
        };
    },
    methods: {
        scrollToEnd() {
            this.$el.querySelector(
                ".container"
            ).scrollTop = this.$el.querySelector(".container").scrollHeight;
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
            axios
                .post(`/chat/${this.idReceiver}`, {
                    message: this.message
                })
                .then(response => {
                    this.checkForUpdate().then(response => {
                        this.scrollToEnd();
                        if (response.updated) {
                            this.properBubbles();
                        }
                    });
                    this.message = "";
                })
                .catch(error => {
                    console.log(error);
                    this.message = "";
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
        }
    },
    props: ["messages", "idSender", "idReceiver"],
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
</style>
