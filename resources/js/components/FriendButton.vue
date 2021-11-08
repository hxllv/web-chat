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
            axios
                .post(`/friend/${this.userId}`)
                .then(response => {
                    if (!this.isFriendsData)
                        this.isRequestedData = !this.isRequestedData;
                    else {
                        this.isRequestedData = false;
                        this.isFriendsData = false;
                    }
                })
                .catch(error => console.log(error));
        },
        getText() {
            if (this.isRequestedData) return "Cancel friend request";
            if (this.isFriendsData) return "Unfriend";

            return "Send friend request";
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
