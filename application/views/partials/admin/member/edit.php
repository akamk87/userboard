<div class="content_box">
	<div class="content_box_header">
		<h3>회원 목록 > 수정</h3>
		<ul class="content_box_tabs">
			<li>
				<a href="/admin/member">목록</a>
			</li>
			<li>
				<a href="#" class="selected">수정</a>
			</li>
		</ul>
		<div class="clear"></div>
	</div>
	
	<div class="content_box_content">
		<div class="tab_content">
			<form class="form-horizontal" action="/admin/member/edit/<?php echo $id; ?>" method="post" accept-charset="utf-8">
				<div class="control-group">
					<label class="control-label" for="name">이름</label>
					<div class="controls">
						<?php echo form_input('users[name]', $users->name, 'class="input-medium"'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="phone">연락처</label>
					<div class="controls">
						<?php echo form_input('users[phone]', $users->phone, 'class="input-medium"'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="address">주소</label>
					<div class="controls">
						<?php echo form_input('users[address]', $users->address, 'class="input-xlarge"'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="registration">등록일</label>
					<div class="controls">
						<?php echo form_input('users[registration]', date('Y-m-d', strtotime($users->registration)), 'class="input-small datepicker" readonly'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="period">기간</label>
					<div class="controls">
						<?php echo form_input('users[period]', $users->period, 'class="input-mini" style="text-align:right;"'); ?> <small>개월</small>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fee">등록비</label>
					<div class="controls">
						<?php echo form_input('users[fee]', $users->fee, 'class="input-small" style="text-align:right;"'); ?> <small>원</small>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="locker">락커번호</label>
					<div class="controls">
						<?php echo form_input('users[locker]', $users->locker, 'class="input-mini"'); ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="memo">비고</label>
					<div class="controls">
						<?php echo form_input('users[memo]', $users->memo, 'class="input-xlarge"'); ?>
					</div>
				</div>
				<div class="control-group" style="margin-top:30px;">
					<label class="control-label"></label>
					<div class="controls">
						<input type="submit" value="저장" class="btn btn-info">
						<a href="/admin/member"><input type="button" value="취소" class="btn btn-inverse" style="margin-left:10px;"></a>
					</div>
				</div>
			</fieldset>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	
</script>