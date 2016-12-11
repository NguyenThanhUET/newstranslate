$(document).ready(function() {
  //LOGIN NORMAL
  var animating = false,
      submitPhase1 = 1100,
      submitPhase2 = 400,
      $login = $(".login");
  //Login button style handler
  $(document).on("click", ".login__submit", function(e) {
    loginNormal();
    if (animating) return;
    animating = true;
    var that = this;
    $(that).addClass("processing");
    setTimeout(function() {
      setTimeout(function() {
      }, submitPhase2 - 70);
    }, submitPhase1);
  });
  //LOGIN VIA GOOGLE
  //Google login start

/*  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '773322437089-40m4gd24cjt0det4srp979hletu5iiss.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('btn-login-google'));
    });
  };
  startApp();*/
  //Google login end
  //Facebook login start
  //Facebook login end

  //call google login

});
//google login button handler
function attachSignin(element) {
  var googleUser = {};
  auth2.attachClickHandler(element, {},
      function(googleUser) {
        createGoogleProfile(googleUser.getBasicProfile());
        //console.log(googleUser.getBasicProfile());
      }, function(error) {
        alert(JSON.stringify(error, undefined, 2));
      });
}
//login normal
function loginNormal(){
  //send to server login
}
//create account via google
function createGoogleProfile(googleUser){
  var profile = {};
  profile['email'] = googleUser.getEmail();
  profile['id']  = googleUser.getId();
  profile['name'] = googleUser.getName();
  profile['givenName'] = googleUser.getGivenName();
  profile['familyName'] = googleUser.getFamilyName();
  profile['imageUrl']  = googleUser.getImageUrl();
  profile['_token'] = $('meta[name="csrf-token"]').attr('content');
  //send to server login
  $.ajax({
    url: '/login/social/google',
    type: 'POST',
    data: profile,
    success: function(data) {
      //called when successful
      $('#ajaxphp-results').html(data);
    },
    error: function(e) {
      //called when there is an error
      //console.log(e.message);
    }
  });
}