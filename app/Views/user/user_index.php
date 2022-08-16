<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Пользовательский интерфейс</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
	<div id="wsocket"></div>
	<div class="container" id="user">
		<div class="col-lg-4 offset-lg-4 bg-secondary">
			<h3 class="text-white text-center mb-4" v-text="MenuHeaderText"></h3>
		</div>
		<div class="row justify-content-between bg-white mt-4">
			<div class="col-auto"  v-for="(but, index) in MenuButtons">
				<button class="btn btn-sm btn-outline-success" v-text="but.name" v-on:click="setAction(index)" v-if="CurrentActiveAction_ind!==index"></button>
				<button class="btn btn-sm btn-primary" v-text="but.name" v-else></button>
			</div>
		</div>
		<div class="container">
			<!-- templates -->
		</div>
	</div>
	<script>
		var Connect = new Vue({
			el:"#wsocket",
			data:{
				Socket: null,
				ToSay:null
			},
			methods:{
				connecttowss: function(pathToSRV,userIdentity){
					const self = this;
					self.Socket =  new WebSocket('wss://'+pathToSRV+':27800?user='+userIdentity),
							self.Socket.onmessage = function (event) {
								let income = JSON.parse(event.data)
								if(income.action=='Ping') {
									let messPong={ 'action':'Pong' }
									self.Socket.send(JSON.stringify(messPong))
									if(self.ToSay!==null){
										let message = {'action':self.ToSay}
										self.Socket.send(JSON.stringify(message))
									}
								}
								else{UserApp.receiverMethod(income)}
							};
				},
			},
			
		})
	</script>
	<script>
		const UserApp = new Vue({
			el:'#user',
			data:{
				
				CurrentActiveAction_ind:null,
				PathToServer:null,
				UserIdentity:null,
				MenuButtons:[{name:'RealTime',action:'real'},{name:'QueryProject',action:'setActualProject'},{name:'Settings',action:'settings'}],
				MenuHeaderText:'Основные действия'
			},
			methods:{
				getMainMenu() {
					console.log('getting Menu')
				},
				setAction(ind){
				 this.CurrentActiveAction_ind=ind
				},
				receiverMethod(data)
				{
					console.log(data)
				}
			},
			mounted(){
				this.getMainMenu()
				Connect.connecttowss('debug.gfmweb.ru',1)
			}
		});
	</script>
</body>
</html>
