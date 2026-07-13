window.addEventListener("load", function(){

    const splash = document.getElementById("splashScreen");

    const video = document.getElementById("introVideo");

    if(video){

        video.onended = function(){

            splash.style.opacity="0";

            setTimeout(function(){

                splash.remove();

            },800);

        };

    }

});