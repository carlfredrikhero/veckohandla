$(function(){
	
	$(document).bind('FBSDKLoaded', function() {

        var User = new FacebookUser();
		
		// Router
		var AppRouter = Backbone.Router.extend({

			routes:{
				"":"root",
				"login":"login"
			},

			root:function () {
				/*if (!User.isConnected()){
					this.navigate("login", {trigger: true, replace: true});
					return;
				}*/
				console.log('route-root');
				$('.container').html(_.template($('#route-root').html()));
			},

			login:function (id) {
				$('.container').html(_.template($('#route-login').html()));
				console.log('route-login');
			}
		});

		var app = new AppRouter();
		
		Backbone.history.start();
		
		$('.login').click(function(){
			User.login();
		});
		
		$('.logout').click(function(){
			User.logout();
		});
		
		User.on('facebook:connected', function(u, response){
			console.log('facebook:connected');
		});
		
		User.on('facebook:disconnected', function(u, response){
			console.log('facebook:disconnected');
		});
		
		User.updateLoginStatus();
		
		
    });
});