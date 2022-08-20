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
								<td v-for="(element) in row" v-if="element.hidden==false" class="text-wrap" v-text="element.value" role="button"></td>
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
				</div>
				<inline_modal :props_data="properties" ></inline_modal>
			</div>
			<script src="/front_app/admin.js"></script>
		</body>
</html>
