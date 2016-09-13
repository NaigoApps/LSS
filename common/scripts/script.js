$(document).ready(function () {

    $(".btn-fade-container").mouseenter(fadeIn);
    $(".btn-fade-container").mouseleave(fadeOut);
    
});

function loginCheck(){
    auth2 = gapi.auth2.getAuthInstance();/*
    var client = gapi.auth2.init({
        client_id: '487942486199-ur74tmud23ud4fvdr7ao871ovomhg27l.apps.googleusercontent.com'
    });*/
    console.log(GoogleAuth.isSignedIn.get());
}

function fadeOut() {
    $(this).children(".btn-fade").animate(
            {
                width: '100%',
                height: '100%',
                left: '0%',
                top: '0%'
            },
    500);
}

function fadeIn() {

    $(this).children(".btn-fade").animate(
            {
                width: '90%',
                height: '90%',
                left: '5%',
                top: '5%'
            },
    500);
}

function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    console.log("ID: " + profile.getId());
    var name = profile.getName();
    var email = profile.getEmail();
    console.log("Name: " + profile.getName());
    console.log("Image URL: " + profile.getImageUrl());
    console.log("Email: " + profile.getEmail());
    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    console.log("ID Token: " + id_token);
};