<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<title>Вход</title>
</head>
<body>
	<div class="container" id="login">
		<div class="container mt-4 mb-4">
			<div class="card">
				<div class="card-body">
					<div class="container">
						<div class="row justify-content-between">
				<div class="col-lg-4" v-for="(item, index) in logMode">
					<div class="row">
						<button v-if="index==activeModeIndex" class="btn btn-info" v-text="item.name"></button>
						<button v-else class="btn btn-outline-info" v-text="item.name" v-on:click="setActiveMode(index)"></button>
					</div>
				</div>
			</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container mt-4">
			<template v-if="formLoginData!==null">
				<div class="card">
					<div class="card-header"><h3 class="text-center text-dark" v-text="formLoginData.form_header"></h3></div>
					<div class="card-body">
						<template v-if="formErrors!==null">
							<p class="text-center text-danger" v-text="formErrors"></p>
						</template>
						<form id="form" :action="formLoginData.action[formOperationIndex]" :method="formLoginData.method" v-on:submit.prevent="processForm">
							<div class="container mt-2 mb-2" v-for="(field, index) in formLoginData.form_fields[formOperationIndex]">
								<label :for="'field_'+index" v-text="field.label"></label>
								<input :id="'field_'+index" :type="field.type" :name="field.name" class="form-control" required/>
							</div>
							<hr/>
							<div class="row justify-content-center">
								<div class="col-sm-6 col-lg-4">
									<div class="row">
										<button class="btn  btn-success" type="submit" v-text="formLoginData.submit_btn_txt"></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</template>
		</div>
	</div>
<script>
	const App = new Vue({
		el:'#login',
		data:{
			activeModeIndex:1, // Активная вкладка по умолчанию
			logMode:[{'name':'Админка','mode':'admin'},{'name':'Логи','mode':'logs'}], //Виды вкладок
			formOperationIndex:0, // Шаг начала работы с операциями формы
			formLoginData:null, // Массив полей и прочие свойства формы
			formOperations:null, // Возможные действия формы
			formErrors:null, // Login errors
			TelegramUserID:null
		},
		methods:{
			setActiveMode(index) //Меняет активность форм входа
			{
				this.activeModeIndex = index
				this.requestLoginFields()
			},
			requestLoginFields(){ //Запрашивает поля и действия для формы
				const FormData = {'mode':this.logMode[this.activeModeIndex].mode}
				const urI = '/login/getLoginForm'
				axios.post(urI,FormData).then(res=>{this.formLoginData = res.data})
			},
			processForm(){
				const urI = this.formLoginData.action[this.formOperationIndex]
				var forma = document.forms[0]
				const fieldsCount =  forma.elements.length-1
				const FormDat = {}
				const self = this
				for(let i = 0; i < fieldsCount; i++) {
					FormDat[forma.elements[i].name] = forma.elements[i].value
					FormDat['telegram'] = self.TelegramUserID
				}
				axios.post(urI,FormDat).then(res=>{
					if(res.data.errors == null){
						if(self.formOperationIndex+1 < self.formLoginData.form_fields.length){
							self.formOperationIndex++
							self.TelegramUserID = res.data.data.user_telegram_id
							self.formErrors = null
						}
						else{
							window.location.replace(res.data.data);
						}
					}
					else{
						self.formErrors = res.data.errors[0]
					}
				})
				
			}
		},
		mounted:function(){
			this.requestLoginFields()
		}
	});
</script>
</body>
</html>
