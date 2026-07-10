let deferredPrompt = null;

window.addEventListener("beforeinstallprompt", (e) => {

    e.preventDefault();

    deferredPrompt = e;

    document.getElementById("installApp").style.display = "block";

});

function installPWA() {

    if (!deferredPrompt) {
        alert("Install is not available on this device/browser yet.");
        return;
    }

    deferredPrompt.prompt();

    deferredPrompt.userChoice.then(() => {
        deferredPrompt = null;
        document.getElementById("installApp").style.display = "none";
    });

}