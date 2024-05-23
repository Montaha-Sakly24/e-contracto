<template>
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="four_zero_four_bg">
                        <h1>404</h1>
                    </div>

                    <div class="contant_box_404">
                        <h3>Looks like you're lost</h3>
                        <p>The page you are looking for is not available!</p>
                        <button
                            @click.prevent="backToDashboard"
                            class="link_404"
                        >
                            Go to Home
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    methods: {
        backToDashboard() {
            const role = localStorage.getItem("role");
            if (role == "Superadmin") {
                this.$router.push({ name: "SuperadminDashboard" });
            } else if (role == "Admin") {
                this.$router.push({ name: "AdminDashboard" });
            } else if (role == "Simple User") {
                this.$router.push({ name: "Home" });
            } else {
                if (!role) {
                    this.$router.push({ name: "Login" });
                }
            }
        },
    },
};
</script>
<style scoped>
.page_404 {
    padding: 50px 0;
    background: #fff;
    font-family: "Arvo", serif;
}

.four_zero_four_bg h1 {
    font-size: 120px;
    margin: 0;
}

.contant_box_404 {
    margin-top: 20px;
}

.contant_box_404 h3 {
    font-size: 36px;
    margin-bottom: 10px;
}

.contant_box_404 p {
    font-size: 18px;
    margin-bottom: 20px;
}

.link_404 {
    color: #fff;
    padding: 12px 24px;
    background: blue;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background 0.3s;
}

.link_404:hover {
    background: rgb(70, 156, 243);
}
</style>
