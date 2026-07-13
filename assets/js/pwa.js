let deferredPrompt = null;

window.addEventListener("beforeinstallprompt", (e)=>{

    e.preventDefault();

    deferredPrompt = e;

    const btn = document.getElementById("installApp");

    if(btn){

        btn.style.display="block";

    }

});

function installPWA(){

    if(!deferredPrompt){

        alert("Install option is not available.");

        return;

    }

    deferredPrompt.prompt();

    deferredPrompt.userChoice.then(choice=>{

        deferredPrompt=null;

        document.getElementById("installApp").style.display="none";

    });

}

window.addEventListener("appinstalled",()=>{

    console.log("HRMS Installed");

});