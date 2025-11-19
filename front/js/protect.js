// protect.js
document.addEventListener("DOMContentLoaded", () => {
    fetch("../back/api/check_session.php")
        .then(res => res.json())
        .then(data => {
            if (!data.logged) {
                window.location.href = "login.html";
            }
        })
        .catch(() => {
            window.location.href = "login.html";
        });
});
