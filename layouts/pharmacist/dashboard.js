
const notificationBtn = document.getElementById("notificationBtn");
const notificationPopup = document.getElementById("notificationPopup");

notificationBtn.addEventListener("click", () => {
    notificationPopup.classList.toggle("show");
    profilePopup.classList.remove("show");
});

const profileBtn = document.getElementById("profileBtn");
const profilePopup = document.getElementById("profilePopup");

profileBtn.addEventListener("click", () => {
    profilePopup.classList.toggle("show");
    notificationPopup.classList.remove("show");
});

document.addEventListener("click", (e) => {

    if(
        !notificationBtn.contains(e.target) &&
        !notificationPopup.contains(e.target)
    ){
        notificationPopup.classList.remove("show");
    }

    if(
        !profileBtn.contains(e.target) &&
        !profilePopup.contains(e.target)
    ){
        profilePopup.classList.remove("show");
    }

});
