<?php

	$Users = [
		1 => [
			'steam' => '76561198884918410',		// Обязательно указывать STEAMID64
		//	'vk' => '',				// Айди страницы вк
			'nick' => 'gm0',					// Отображаемый ник
			'role' => 'Основатель проекта'		// Отображаемая роль
		],
		2 => [
		 	'steam' => '76561198239291046',
		// 	'vk' => '',
		 	'nick' => 'FurZich',
		 	'role' => 'Главный Администратор'
		],
		3 => [
		 	'steam' => '76561198071341378',
		// 	'vk' => '',
		 	'nick' => 'UkaUkka',
		 	'role' => 'Главный Администратор'
		// ]
	];

	// Если вам нужно отображать больше или меньше блоков с админами, то удалите или добавьте их здесь, при добавление нового блока не забывайте изменять цифры!

	// Открывать ли ссылки на соц сети в новой вкладке
	// 0 - В текущей вкладке
	// 1 - В новой вкладке
	$new_tab = 1;

	// Не трогать
	switch ($new_tab) {
		case 0:
			$blank = '_self';
			break;
		case 1:
			$blank = '_blank';
			break;
	}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="badge"><?php echo $Translate->get_translate_module_phrase('module_page_team_project', '_Team')?></h5></div>
				<div class="row blockt">
					<? foreach ($Users as $value) { ?>
						<div class="main_blockt">
							<div class="avatar">
								<img class="pointer" onclick="location.href = '<?php echo $General->arr_general['site'] ?>?page=profiles&profile=<?php echo $value['steam'];?>&search=1';"<?php if ( $General->arr_general['avatars'] == 1)?> data-src="<?php echo $General->getAvatar( ($value['steam'] ), 1 )?>">
							</div>
							<div class="name_blockt"> <?php echo $value['nick'] ?></div>
							<div class="role_blockt"><?php echo $value['role'];?></div>
							<div class="social_blockt">
								
								<?php if ($value['steam']) {
									$steam = $value['steam'];
									echo "<a class='icons' href='https://steamcommunity.com/profiles/$steam' target='$blank'><img src='app/modules/module_page_team_project/assets/img/steam.svg'></a>";
									} else { echo ''; }?>
								
								<?php if ($value['vk']) {
									$vk = $value['vk'];
									echo "<a class='icons' href='https://vk.com/$vk' target='$blank'><img src='app/modules/module_page_team_project/assets/img/vk.svg'></a>";
									} else { echo ''; }?>
								
							</div>
						</div>
					<? } ?>
				</div>
		</div>
	</div>
</div>