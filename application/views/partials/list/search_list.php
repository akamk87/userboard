<div class="main-wrap pull-left">
	<div class="search list">
		<h1><i class="icon-big icon-search"></i>전체 찾아보기</h1>
		<form class="form-search well">
			<p>
				예비창업자, 스타트업, 엔젤 및 투자자 모두를 찾아볼 수 있습니다.<br/>
				검색하고자 하는 회사명 또는 기업번호를 입력하고 [검색]을 눌러주세요.
			</p>
			<input type="text" class="input" name="sv" style="width:400px;" value="<?php echo $sv; ?>" placeholder="회사이름 또는 기업번호">
			<button type="submit" class="btn btn-info">검색</button>
		</form>
		<ul>
<?php
if(count($search_lists) > 0):
	foreach($search_lists as $search_list):
?>
			<li class="clearfix">
				<a href="/timeline/<?php echo $search_list->type_code->key . '/' . $search_list->serial_number ?>" class="card pull-left">
					<div class="card-profile">
						<img src=<?php echo $search_list->upload ? '/thumbnail/' . $search_list->upload->id . '?w=150&h=150' : '/public/images/avatar.png'; ?> alt="">
					</div>
					<strong><?php echo $search_list->name; ?></strong>
					<em><?php echo $search_list->type_code->name; ?></em>
				</a>
				<div class="inner-wrap">
					<div class="meta">
						<a href="/timeline/<?php echo $search_list->type_code->key . '/' . $search_list->serial_number ?>" class=""><?php echo $search_list->name; ?></a>
						<?php
							$last_login = $search_list->last_login_at ? strtotime($search_list->last_login_at) : strtotime($search_list->created_at);
							if(time() > $last_login + (60 * 60 * 24 * 90)) {
								// live off
								echo '<i class="icon icon-warning-s"></i>';
							}
		                ?>
						 · <span>
						 <?php switch ($search_list->type_code->key) {
								case 'preparatory': echo 'PRE'; break;
								case 'startup': echo 'COM'; break;
								case 'angel_investor': echo 'ANGEL'; break;
								case 'corporative_investor': echo 'VC'; break;
								default: break;
						 } ?>
						 <?php echo $search_list->serial_number; ?></span>
						<p class="desc"><?php echo $search_list->biography; ?></p>
					</div>
					<h3><i class="icon icon-comment"></i> 최근소식</h3>
					<?php if($search_list->timeline): ?>
						<p class="feed"><?php echo $search_list->timeline->message; ?></p>
						<span class="date"><?php echo date('Y. m. d', strtotime($search_list->timeline->created_at)); ?></span>
					<?php else: ?>
						<p class="feed">최근소식이 없습니다.</p>
					<?php endif; ?>
				</div>
			</li>
<?php
	endforeach;
else:
?>
			<li class="none-result">
				<p>결과가 없습니다</p>
			</li>
<?php endif; ?>
		</ul>
		<?php echo $pagination; ?>
	</div>
</div>