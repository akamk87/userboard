<div class="content_box">
	<div class="content_box_header">
		<h3>회원 목록</h3>
		<ul class="content_box_tabs">
			<li>
				<a href="#" class="default_tab selected">목록</a>
			</li>
			<li>
				<a href="#">수정</a>
			</li>
		</ul>
		<div class="clear"></div>
	</div>
	
	<div class="content_box_content">
		<div class="tab_content">
			<div style="text-align:right;">
				<form class="form-search" action="/admin/member" method="get" accept-charset="utf-8">
					기간: <?php echo form_dropdown('periods', $period_list, $periods, 'class="select_category" style="width:auto;"'); ?>개월 
					<div class="input-append">
						<input type="text" class="span2 search-query" name="sv" value="<?php echo $sv; ?>" placeholder="이름">
						<button type="submit" class="btn"><i class="icon-search"></i></button>
					</div>
				</form>
			</div>
			<table>
				<thead>
					<tr>
					    <th>번호</th>
						<th>이름</th>
						<th>등록일</th>
						<th>기간</th>
						<th>주소</th>
						<th>연락처</th>
						<th>등록비</th>
						<th>비고</th>
						<th style="width:100px;">수정 / 삭제</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="9">
							<?php echo $pagination; ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
<?php
if(isset($users) && count($users) > 0):
	foreach($users as $user):
?>
					<tr>
					    <td><?php echo $user->id; ?></td>
						<td><?php echo $user->name; ?></td>
						<td><?php echo date('y.m.d', strtotime($user->registration)); ?></td>
						<td><?php echo $user->period; ?></td>
						<td><?php echo $user->address; ?></td>
						<td><?php echo $user->phone; ?></td>
						<td><?php echo $user->fee; ?></td>
						<td><?php echo $user->memo; ?></td>
						<td>
							<a href="/admin/member/edit/<?php echo $user->id; ?>"><input type="button" value="수정" class="btn btn-small btn-warning"></a>
							<a href="/admin/member/delete/<?php echo $user->id; ?>" class="member_delete"><input type="button" value="삭제" class="btn btn-small btn-danger"></a>
						</td>
					</tr>
<?php
	endforeach;
else:
?>
					<tr>
						<td colspan="9" style="text-align:center;">결과가 없습니다</td>
					</tr>
<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('a.member_delete').click(function() {
		if (confirm("정말 삭제 하시겠습니까?")) {
			$.getJSON($(this).attr('href'), {}, function(response) {
				if(response) {
					if(response.result) {
						//alert(response.message);
						window.location.reload();
					} else {
						alert(response.message);
					}
				} else {
					alert('삭제 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
				}
			});
			return false;
		} else {
			return false;
		}
	});
});
</script> 
