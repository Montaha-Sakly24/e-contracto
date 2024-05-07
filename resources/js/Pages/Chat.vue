<script>
import axios from "axios";

export default {
    data() {
        return {
            messages: [],
            newMessage: "",
            userId: null,
        };
    },
    mounted() {
        this.initializeChat();
    },
    methods: {
        initializeChat() {
            this.userId = this.fetchUserId();
            if (!this.userId) {
                console.error("User ID is not available. Cannot join chat.");
                return;
            }
            this.setupEchoListener();
        },
        fetchUserId() {
            const userId = localStorage.getItem("userId");
            console.log("Fetched User ID from localStorage:", userId);
            return userId ? parseInt(userId) : null;
        },

        setupEchoListener() {
            window.Echo.private(`privateChat.${this.userId}`).listen(
                "MessageSent",
                (event) => {
                    this.messages.push(event.message);
                }
            );
        },
        leaveEchoChannel() {
            if (this.userId && window.Echo) {
                window.Echo.leave(`privateChat.${this.userId}`);
            }
        },
        sendMessage() {
            if (!this.newMessage.trim()) return;
            axios
                .post("/api/messages", {
                    message: this.newMessage,
                    user_id: this.userId,
                })
                .then((response) => {
                    this.messages.push(response.data.data);
                    this.newMessage = "";
                })
                .catch((error) => {
                    console.error("Error sending message:", error);
                });
        },
    },
};
</script>
