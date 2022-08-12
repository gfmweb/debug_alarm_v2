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
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
									<button v-if="index==adminActiveAcnionIndex" class="btn btn-success" v-text="item.name" disabled></button>
									<button v-else class="btn btn-outline-success" v-text="item.name" v-on:click="setActiveAction(index)"></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	const AdminApp = new Vue({
		el:'#admin',
		data:{
			menu_text:null,
			adminActiveAction:null,
			adminActiveAcnionIndex:null,
			adminActions:null,
			activeDataHeaderText:null,
			activeDataContentBlock:null,
			activeDataRequests:null
		},
		methods:{
			setActiveAction(index){
				this.adminActiveAcnionIndex = index
				this.makeAction(index)
			},
			getMainMenu(){
				axios.post('/admin/init').then(res=>{
					this.menu_text = res.data.menu_text
					this.adminActions = res.data.adminActions
				})
			},
			makeAction(index){
				const self = this
				if(self.adminActions[index].method == 'GET')
				{
					axios.get(self.adminActions[index].urI).then(
							res=>{
								if(self.adminActions[index].after.action == 'show_content') {
									console.log('must_show_content')
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
									console.log('must_show_content')
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
		{
			this.getMainMenu()
		}
	})
</script>
</body>
</html>
