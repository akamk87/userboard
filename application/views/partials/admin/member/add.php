<div class="content_box">
	<div class="content_box_header">
		<h3>신규 회원 추가</h3>
		<div class="clear"></div>
	</div>
	
	<div class="content_box_content">
		<div class="tab_content">
			<form class="form-horizontal registration" action="/admin/add" method="post" accept-charset="utf-8">
				<div class="control-group">
					<label class="control-label" for="name">이름</label>
					<div class="controls">
						<input type="text" name="users[name]" class="input-medium">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="phone">연락처</label>
					<div class="controls">
						<input type="text" name="users[phone]" class="input-medium">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="address">주소</label>
					<div class="controls">
						<input type="text" name="users[address]" class="input-xlarge">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="registration">등록일</label>
					<div class="controls">
						<input type="text" name="users[registration]" class="input-small datepicker">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="period">기간</label>
					<div class="controls">
						<input type="text" name="users[period]" class="input-mini" style="text-align:right;"> <small>개월</small>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fee">등록비</label>
					<div class="controls">
						<input type="text" name="users[fee]" class="input-small" style="text-align:right;"> <small>원</small>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="locker">락커번호</label>
					<div class="controls">
						<input type="text" name="users[locker]" class="input-mini">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="memo">비고</label>
					<div class="controls">
						<input type="text" name="users[memo]" class="input-xlarge">
					</div>
				</div>
				<div class="control-group" style="margin-top:30px;">
					<label class="control-label"></label>
					<div class="controls">
						<input type="submit" value="추가" class="btn btn-info">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function (){
/*	var fv = new form_validation();
	fv.initialize({
		ruleset: {
			'name': { label: '이름', rule: { required: true } },
			'phone': { label: '연락처', rule: { numeric: true } },
			'month': { label: '기간', rule: { required: true, numeric: true } }
		}
	});
*/	
	// 등록일 dateapicker
	$('input.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
	var now = new Date();
	var month = (now.getMonth() + 1);               
	var day = now.getDate();
	if(month < 10) month = "0" + month;
	if(day < 10) day = "0" + day;
	var today = now.getFullYear() + '-' + month + '-' + day;
	$('input.datepicker').val(today);
	
	$('form.registration').submit(function() {
//		alert('준비중입니다.');
//		return false;
	});
});
</script>