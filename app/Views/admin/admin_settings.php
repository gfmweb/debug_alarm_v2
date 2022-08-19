<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Админ настройки</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8/dist/vue.js"></script>
	<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.7.8"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>
<body>
<div class="container" id="admin_settings">
	<div class="col-lg-6 offset-lg-3">
		<div class="container mt-5 mb-5 bg-secondary shadow-sm text-white">
			<div class="row justify-content-center">
				<a href="/admin" style="text-decoration: none">
					<h3 class="text-center text-white" role="button">Вернуться в админку</h3>
				</a>
			</div>
		</div>
	</div>
	<div class="row mt-4 mb-4 justify-content-between">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header bg-info">
						<h4 class="h4 text-center"><i class="fa-brands fa-telegram"></i> Телеграм БОТ</h4>
					</div>
					<div class="card-body">
						<template v-if="hookResult!==null">
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong v-text="hookResult.description"></strong>
								<button type="button" class="btn-close" v-on:click="dropHook" aria-label="Close"></button>
							</div>
						</template>
						<div class="row justify-content-center">
							<button class="btn btn-outline-success btn-sm btn-rounded" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hook_modal">Установить новый адрес WEB HOOK</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
			<div class="card">
				<div class="card-header bg-warning">
					<h4 class="h4 text-center"><i class="fa-solid fa-gear"></i> Глобально сервис</h4>
				</div>
				<div class="card-body">
					<template v-if="serviceMode=='stop'">
						<div class="row">
							<button class="btn btn-outline-primary mb-4" v-on:click="redisInit">Инициализация Redis</button>
						</div>
					</template>
					<div class="form-check form-switch">
						<div>
							<input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" v-if="serviceMode=='stop'" v-on:change="onOffService">
							<input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" v-else v-on:change="onOffService" checked>
							<label class="form-check-label" for="flexSwitchCheckDefault">Состояни сервиса</label>
						</div>
					</div>
				</div>
			</div>
		</div>
			<div class="col-lg-4">
			<div class="card">
				<div class="card-header bg-success text-center">
					<h4 class="h4 text-center"><i class="fa-solid fa-lock"></i> Пароль администратора</h4>
					<i class="fa-solid fa-eye" v-if="inputstypes == 'text'" role="button" v-on:click="changeInputTypes"></i>
					<i class="fa-solid fa-eye-slash" v-else role="button" v-on:click="changeInputTypes"></i>
				</div>
				<template v-if="alertForm!==null">
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong v-text="alertForm.field"></strong> {{alertForm.text}}
						<button type="button" class="btn-close" v-on:click="dropAlert" aria-label="Close"></button>
					</div>
				</template>
				<template v-if="passwordResults==null">
						<form onsubmit="Settings.setNewPassword(); return false">
							<div class="card-body">
								<label>Текущий пароль</label>
								<input :type="inputstypes" name="current_password" v-model.trim="currentPassword" class="form-control" required placeholder="Введите Ваш текущий пароль"/>
								<label>Новый пароль</label>
								<input :type="inputstypes" name="new_password" v-model.trim="newPassword" class="form-control" required placeholder="Введите Ваш новый пароль"/>
								<label>Повторите новый пароль</label>
								<input :type="inputstypes" name="repeat_password" v-model.trim="confirmPassword" class="form-control" required placeholder="Повторите Ваш новый пароль"/>
							</div>
							<div class="card-footer">
								<div class="row">
									<div class="col-4 offset-4">
										<button class="btn btn-success" type="submit" >Сохранить</button>
									</div>
								</div>
							</div>
						</form>
				</template>
				<template v-else>
					<div :class="'card-body '+passwordResults.background">
						<p v-text="passwordResults.text" class="text-white text-center"></p>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-4 offset-4">
								<button class="btn btn-success" type="button" v-on:click="dropPasswordErrors" v-text="passwordResults.btn_text"></button>
							</div>
						</div>
					</div>
				</template>
			</div>
		</div>
	</div>
	<div class="modal fade" id="hook_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Установка адреса Web Hooks для Телеграм бота</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<div class="input-group-text" v-text="hookPrefix.main" v-if="hookPrefix!==null"></div>
						<input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Имя роута" v-model="hookPrefix.current" v-if="hookPrefix!==null">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cls_btn_hook">Закрыть</button>
					<button type="button" class="btn btn-primary" v-if="hookPrefix!==null" v-on:click="webHookSet">Сохранить изменения</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/front_app/admin_settings.js"></script>
</body>
</html>
