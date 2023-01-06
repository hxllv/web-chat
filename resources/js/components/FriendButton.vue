<template>
    <button class="btn btn-primary" @click="friend">
        {{ getText() }}
    </button>
</template>

<script>
export default {
    props: ["userId", "isRequested", "isFriends"],
    methods: {
        friend() {
            if (this.isFriendsData) {
                axios.delete(`/friend/${this.userId}`).then(response => {
                    this.update();
                });
                return;
            }

            axios
                .post(`/friend/${this.userId}`)
                .then(response => {
                    this.update();
                })
                .catch(error => console.log(error));
        },
        getText() {
            if (this.isRequestedData) return "Cancel friend request";
            if (this.isFriendsData) return "Unfriend";

            return "Send friend request";
        },
        update() {
            if (!this.isFriendsData)
                this.isRequestedData = !this.isRequestedData;
            else {
                this.isRequestedData = false;
                this.isFriendsData = false;
            }
        }
    },
    data() {
        return {
            isRequestedData: this.isRequested,
            isFriendsData: this.isFriends
        };
    }
};
</script>
