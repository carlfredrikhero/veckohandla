$(function(){
	$(document).bind('FBSDKLoaded', function() {
		var User = new FacebookUser();
	
		$('.content').on('click', '.fb-login', function(){
			User.login();
		});
		$('.content').on('click', '.fb-logout', function(){
			User.logout();
		});
		
		User.on('change', function(model, response){
			$('.content').find('.facebook').remove();
			if (User.isConnected()){
				Items.fetch({
					data: {
						date: $('#date').val(),
						user_id: User.get('id')
					}
				});

				$('.content').append(
					_.template(
						$('#fb-logout-template').html(),
						{name: User.get('name')}
					)
				);
			} else {
				Items.reset();
				$('.content').prepend(
					_.template($('#fb-login-template').html())
				);
			}
		});
		
		User.updateLoginStatus();
		
		// TODO http://stackoverflow.com/questions/2385332/jquery-datepicker-highlight-dates
		// highlight dates with shopping list
		$('#date').datepicker({
			dateFormat: 'yy-mm-dd'
		});

		$('.select-date').click(function(){
			$('#date').datepicker('show');
		});

		var ItemModel = Backbone.Model.extend({
			defaults: {
				"label":  "",
				"tag":    "Okategoriserade",
				"done":   false,
				"date": $('#date').val()
			},
			initialize: function(){
				this.set('user_id', User.get('id') || 0);
			},
			toggle: function(){
				this.save({'done': !this.get('done')});
			}
		});

		var ItemCollection = Backbone.Collection.extend({
			url: '/items',
			model: ItemModel,
			comparator: function(item){
				if (item.get('tag').toLowerCase() == "okategoriserade"){
					return 'zzz';
				}

				return item.get('tag').toLowerCase();
			},
			initialize: function(){
				this.listenTo(this, 'request', this.loading);
				this.listenTo(this, 'sync', this.notLoading);
			},
			loading: function(){
				$('#loading').show();
			},
			notLoading: function(){
				$('#loading').hide();
			}

		});

		var Items = new ItemCollection;

		var ItemView = Backbone.View.extend({
			tagName: "li",
			template: _.template($('#item-template').html()),
			initalize: function(){
				this.listenTo(this.model, 'change', this.render);
			},
			render: function(){
				this.$el.html(this.template(this.model.toJSON()));
				this.$el.toggleClass('done', this.model.get('done'));
				return this;
			},
			events: {
				"click input[type=\"checkbox\"]": "toggle",
				"blur input[type=\"text\"]": "save",
				"keypress input[type=\"text\"]": "saveOnEnter",
				"click .item-remove": "destroy"
			},
			toggle: function(e){
				this.model.toggle();
				this.$el.toggleClass('done', this.model.get('done'));
			},
			save: function(e){
				var label_val = this.$el.find('.item-label').val();
				var tag_val = this.$el.find('.item-tag').val();
				if (this.model.get('label') != label_val ||
					this.model.get('tag') != tag_val){
					if (label_val == ''){
						this.destroy();
						return;
					}
					this.model.set({
						label: label_val,
						tag: tag_val
					});
					this.model.save();
					Items.sort();
				} else {
					console.log('no need to save');
				}
			},
			saveOnEnter: function(e){
				if (e.keyCode != 13) return;
				console.log('saveOnEnter');
				this.$el.find('input:focus').trigger('blur');
			},
			destroy: function(){
				var view = this;
				this.model.destroy({
					//wait: true,
					success: function(model, response, options){
						view.$el.fadeOut(300, function(){
							view.remove();
							Items.sort();
							Items.trigger('sync');
						});
					},
					error: function(model, jqXHR, options){
						console.error(jqXHR);
					}
				});
			}
		});

		var AppView = Backbone.View.extend({
			el: $('#shoplistapp'),
			dp: $('#date'),
			events: {
				"keypress #add": "createOnEnter",
				"blur .tag-header input": "bulkUpdateTag",
				"change #date": "setDate"
			},
			initialize: function(){
				this.input = this.$('#add');
				this.listenTo(Items, 'add', this.addOne);
				this.listenTo(Items, 'reset', this.addAll);
				this.listenTo(Items, 'all', this.render);
				this.listenTo(Items, 'sort', this.addAll);
			},
			createOnEnter: function(e){
				if (e.keyCode != 13 || this.input.val() == '') return;
				Items.create({label: this.input.val()});
				this.input.val('');
			},
			addOne: function(item){
				var view = new ItemView({model: item});

				// determine if we need to insert tag-header
				var currTag = item.get('tag');
				var prevItem = Items.at(Items.indexOf(item) - 1);
				// if the isn't a prevItem
				if (typeof prevItem === "undefined" || prevItem.get('tag') != item.get('tag')){
					this.$("#shopping-list").append(
						_.template(
							$('#tag-header-template').html(),
							{headertext: item.get('tag')}
						)
					);
				}

				//prevTag = Items.at(Items.indexOf(item);
				this.$("#shopping-list").append(view.render().el);
			},
			addAll: function(){
				this.$("#shopping-list li:gt(0)").remove();
				Items.each(this.addOne);
			},
			bulkUpdateTag: function(ev){
				var $tagInput = $(ev.currentTarget);
				var orgValue = $tagInput.data('org-value');
				var ItemsToUpdate = _(Items.filter(function(item){
					return item.get('tag') == orgValue;
				}));



				ItemsToUpdate.each(function(item, iterator){
					item.save({'tag': $tagInput.val()});

					if ((ItemsToUpdate._wrapped.length - 1) == iterator){
						Items.sort();
					}
				});
			},
			setDate: function(ev){
				var dateObject = $("#date").datepicker("getDate");
				var dateString = $.datepicker.formatDate("d M, yy", dateObject);

				$('.select-date').text(dateString);

				Items.fetch({data: {date: $('#date').val()}});


			}
		});

		var App = new AppView;
	
    });
});