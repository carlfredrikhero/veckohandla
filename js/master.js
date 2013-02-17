$(function(){
	var ItemModel = Backbone.Model.extend({
		defaults: {
			"label":  "",
			"tag":    "Okategoriserade",
			"done":   false
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
			"keypress input[type=\"text\"]": "saveOnEnter"
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
				console.log('save model');
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
		}
	});
	
	var AppView = Backbone.View.extend({
		el: $('#shoplistapp'),
		events: {
			"keypress #add": "createOnEnter",
			"blur .tag-header input": "bulkUpdateTag"
		},
		initialize: function(){
			this.input = this.$('#add');
			this.listenTo(Items, 'add', this.addOne);
			this.listenTo(Items, 'reset', this.addAll);
			this.listenTo(Items, 'all', this.render);
			this.listenTo(Items, 'sort', this.sort);
			
			Items.fetch();
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
			Items.each(this.addOne);
		},
		sort: function(){
			this.$("#shopping-list li:gt(0)").remove();
			this.addAll();
		},
		bulkUpdateTag: function(ev){
			var $tagInput = $(ev.currentTarget);
			var orgValue = $tagInput.data('org-value');
			var ItemsToUpdate = _(Items.filter(function(item){
				return item.get('tag') == orgValue;
			}));
			
			
			console.log($tagInput.val());
			var i = 1;
			
			ItemsToUpdate.each(function(item){
				window.setTimeout(function(){
					item.save({'tag': $tagInput.val()}, {
					error: function(model, xhr, options){
						console.error(model);
						//console.error(xhr);
						//console.error(options);
					},
					success: function(model, response, options){
						console.log(model);
						console.log(response);
						console.log(options);
					}
				});
				},i * 1000);
				i++;
			});
		}
	});
	
	var App = new AppView;
});