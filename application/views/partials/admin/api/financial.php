<div id="financial" class="facebox_popup">
	<h1 class="header">기업 검색</h1>
	<table class="listup">
		<tbody>
			<tr>
				<th>기업이름</th>
				<td><?php echo $financial->name; ?></td>
				<th>기업번호</th>
				<td><?php echo $financial->serial_number; ?></td>
			</tr>
			<tr>
				<th>기업구분</th>
				<td><?php echo $financial->user_type_code_name; ?></td>
				<th>실사일자</th>
				<td><?php echo date('Y. m. d', strtotime($financial->inspected_at)); ?></td>
			</tr>
			<tr>
				<th>매출액</th>
				<td><?php echo $financial->sales_amount; ?></td>
				<th>자산</th>
				<td><?php echo $financial->asset_amount; ?></td>
			</tr>
			<tr>
				<th>영업이익</th>
				<td><?php echo $financial->sales_profit_amount; ?></td>
				<th>부채</th>
				<td><?php echo $financial->debt_amount; ?></td>
			</tr>
			<tr>
				<th>순이익</th>
				<td><?php echo $financial->net_profit_amount; ?></td>
				<th>자본금</th>
				<td><?php echo $financial->capital_amount; ?></td>
			</tr>
			<tr>
				<th>재무제표 자료</th>
				<td colspan="3">
					<ul style="list-style: disc; margin-left: 20px;">
					<?php
					if(count($financial->attachments) > 0) {
						foreach($financial->attachments as $attachment) {
							echo '<li><a href="/download/' . $attachment->id . '">' . $attachment->orig_name . '</a> <small>' . byte_format($attachment->file_size, 1) . '</small></li>';
						}
					} else {
						echo '<li>없음</li>';
					}
					?>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>	
</div>
