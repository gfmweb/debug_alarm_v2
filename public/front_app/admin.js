addEventListener("submit", function(event) {
	event.preventDefault();
}, true);
Vue.component('inline_modal', {
	props: {'props_data': Object},
	computed:{
		header(){return this.props_data.header},
		fields(){return this.props_data.fields},
		form(){return this.props_data.form},
		target_id(){return this.props_data.target_id}
	},

	template: '' +
		'<div class="modal fade" id="inline_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">'+
		'<div class="modal-dialog">'+
		'<div class="modal-content">'+
		'<div class="modal-header">'+
		'<h5 class="modal-title" id="exampleModalLabel" v-text="header"></h5>'+
		'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
		'</div>'+
		'<form :action="form.urI" :method="form.method" onsubmit="AdminApp.process_inline_form();return false;" >'+
		'<div class="modal-body">'+
		'<div class="container mt-3">'+
		'<input type="hidden" :name="target_id.name" :value="target_id.value"/>'+
		'<div class="container mt-3" v-for="item in fields">'+
		'<label v-text="item.placeholder"></label>'+
		'<input :type="item.type" :name="item.name" :value="item.value" :placeholder="item.placeholder" class="form-control" required/>'+
		'</div>'+
		'</div>'+
		'</div>'+
		'<div class="modal-footer">'+
		'<button type="button" class="btn btn-secondary" id="child_modal_close" data-bs-dismiss="modal">Отмена</button>'+
		'<button type="submit" class="btn btn-primary" >Сохранить</button>'+
		'</form>'+
		'</div>'+
		'</div>'+
		'</div>'+

		'</div>'
})
const AdminApp = new Vue({
	el:'#admin',
	data:{
		menu_text:null, // Текст в меню
		adminActiveActionIndex:null, // INDEX Текушей активной вкладки основного меню
		adminActions:null, // Массив доступных экшенов Админа
		activeDataHeaderText:null, // Заголовок активного содержимого
		activeDataContentBlock:null, // Контент активного содержимого
		activeDataContentView:null, // Как показывать активное содержимое
		activeDataRequests:null, // Возможные запросы активного содержимого
		properties:{
			header:'Unknown component',
			target_id:0,
			fields:[],
			form:{urI:'/',method:'POST'}
		} // Набор свойств и форм для компонента инлайнового модального окна действия
	},
	methods:{
		process_form(urI){
			var forma = document.forms[0]
			const fieldsCount =  forma.elements.length-1
			const FormDat = {}
			const self = this
			for(let i = 0; i < fieldsCount; i++) {
				FormDat[forma.elements[i].name] = forma.elements[i].value
			}
			axios.post(urI,FormDat).then(res=>{
				let modalka = document.getElementById('close_modal')
				modalka.click()
				self.makeAction(self.adminActiveActionIndex)
			})
		},
		process_inline_form(){
			var forma = document.forms[1]
			const fieldsCount =  forma.elements.length-1
			const FormDat = {}
			const self = this
			for(let i = 0; i < fieldsCount; i++) {
				FormDat[forma.elements[i].name] = forma.elements[i].value
			}
			let urI = this.properties.form.urI
			axios.post(urI,FormDat).then(res=>{
				self.makeAction(self.adminActiveActionIndex)
				let modalka = document.getElementById('child_modal_close')
				modalka.click()
			})

		},
		setActiveAction(index){
			this.adminActiveActionIndex = index
			this.makeAction(index)
		},
		getMainMenu(){
			axios.get('/admin/init').then(res=>{
				this.menu_text = res.data.menu_text
				this.adminActions = res.data.adminActions
			}).catch(function (error) {
				console.log(error);
				window.location.replace('/login')
			});
		},
		makeInlineAction(urI, method, dependencies,  object, confirmation, confirmation_text)
		{
			if(confirmation == true){
				let choice = confirm(confirmation_text)
				if(!choice){
					return null
				}
			}
			const self = this
			const Form = new FormData()
			dependencies.forEach(function (dep){
				const variable = dep
				object.forEach(function(key){
					if(key.name == variable){
						Form.append(variable,key.value)
					}
				})
			})
			if(method == 'POST') {
				axios.post(urI, Form).then(res => {
					self.makeAction(self.adminActiveActionIndex)
				})
			}
			if(method=='GET'){
				axios.post(urI, Form).then(res => {
					self.properties = res.data
					var myModal = new bootstrap.Modal(document.getElementById('inline_modal'), {
						keyboard: false
					})
					myModal.toggle();
				})
			}
		},
		makeAction(index){
			const self = this
			if(self.adminActions[index].method == 'GET')
			{
				axios.get(self.adminActions[index].urI).then(
					res=>{
						if(self.adminActions[index].after.action == 'show_content') {
							self.activeDataHeaderText = res.data.header
							self.activeDataContentBlock = res.data.content
							self.activeDataContentView = res.data.activeDataContentView
							self.activeDataRequests = res.data.activeDataRequests
						}
						else{
							window.location.replace(self.adminActions[index].after.action);
						}
					}
				)
			}
			if(this.adminActions[index].method == 'POST'){
				axios.post(self.adminActions[index].urI).then(
					res=>{
						if(self.adminActions[index].after.action == 'show_content') {
							self.activeDataHeaderText = res.data.header
							self.activeDataContentBlock = res.data.content
						}
						else{
							window.location.replace(res.data.after.action);
						}
					}
				)
			}

		}
	},
	mounted:function()
	{this.getMainMenu()}
})
