let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e)=>{

    e.preventDefault();

    deferredPrompt=e;

    document.getElementById("installApp").style.display="block";

});

function installPWA(){

    deferredPrompt.prompt();

}