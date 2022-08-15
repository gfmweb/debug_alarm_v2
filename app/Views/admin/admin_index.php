<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Админ панель</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>-->
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
<div class="container" id="admin">
	<div class="col-lg-6 offset-lg-3">
		<div class="container mt-5 mb-5 bg-secondary shadow-sm text-white">
			<div class="row justify-content-center">
				<h3 class="text-center" v-text="menu_text"></h3>
				<div class="container bg-light">
					<div class="row justify-content-between">
						<div class="col-auto" v-for="(item,index) in adminActions">
							<div class="row mt-5 mb-4">
								<button v-if="index==adminActiveActionIndex" class="btn btn-success" v-text="item.name" disabled></button>
								<button v-else class="btn btn-outline-success" v-text="item.name" v-on:click="setActiveAction(index)"></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<template v-if="activeDataHeaderText!==null">
			<div class="row justify-content-center">
				<h3 class="h3 text-center" v-text="activeDataHeaderText"></h3>
			</div>
			<template v-if="activeDataContentView=='CRUD'">
				<table class="table table-bordered table-hover table-success">
					<thead>
					<tr>
						<div class="row justify-content-between mb-3">
							<div class="col-auto" v-for="but in activeDataRequests.outline">
								<button class="btn btn-success" v-text="but.name" data-bs-toggle="modal" :data-bs-target="'#'+but.id" ></button>
								<div v-html="but.template">
								</div>
							</div>
						
						</div>
					</tr>
					<tr class="text-center">
						<th>№</th>
						<th v-for="header in activeDataContentBlock.greeds" v-text="header"></th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="(row, index) in activeDataContentBlock.data">
						<td v-text="index+1" role="button" class="text-center"></td>
						<td v-for="(element) in row" v-if="element.hidden==false" v-text="element.value" role="button"></td>
						<td>
							<div class="row justify-content-between">
								<div class="col-auto" v-for="(act) in activeDataRequests.inline">
									<button :class="act.btn_class" :title="act.label" v-html="act.icon"
									        v-on:click="makeInlineAction(act.urI,
											        act.method,
											        act.dependencies,
											        activeDataContentBlock.data[index],
											        act.confirmation,
											        act.confirmation_text)"></button>
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</template>
		</template>
	</div>
	<inline_modal :props_data="properties" ></inline_modal>
</div>
<script>
	addEventListener("submit", function(event) {
		event.preventDefault();
	}, true);
</script>
<script>
	Vue.component('inline_modal', {
		props: {'props_data': Object},
		computed:{
			header(){return this.props_data.header},
			fields(){return this.props_data.fields},
			form(){return this.props_data.form},
			target_id(){return this.props_data.target_id}
		},
		methods:{
			process_inline_form(){
				console.log('cool')
				console.log('ready for form collect')
				var forma = document.forms[1]
				console.log(forma.elements)
				
			}
		},
		template: '' +
				'<div class="modal fade" id="inline_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">'+
				'<div class="modal-dialog">'+
				'<div class="modal-content">'+
				'<div class="modal-header">'+
				'<h5 class="modal-title" id="exampleModalLabel" v-text="header"></h5>'+
				'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
				'</div>'+
				'<form :action="form.urI" :method="form.method" onsubmit="return false;" >'+
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
				'<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>'+
				'<button type="button" class="btn btn-primary" v-on:click="process_inline_form">Сохранить</button>'+
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
					modalka = document.getElementById('close_modal')
					modalka.click()
					self.makeAction(self.adminActiveActionIndex)
				})
			},
			process_inline_form(){
			
			},
			setActiveAction(index){
				this.adminActiveActionIndex = index
				this.makeAction(index)
			},
			getMainMenu(){
				axios.get('/admin/init').then(res=>{
					this.menu_text = res.data.menu_text
					this.adminActions = res.data.adminActions
				})
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
</script>
</body>
</html>