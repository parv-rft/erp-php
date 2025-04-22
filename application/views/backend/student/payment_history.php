<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i>&nbsp;&nbsp;<?php echo get_phrase('payment_history');?></div>
				<div class="panel-body table-responsive">
 					<table id="example23" class="display nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><div>#</div></th>
								<th><div><?php echo get_phrase('title');?></div></th>
								<th><div><?php echo get_phrase('description');?></div></th>
								<th><div><?php echo get_phrase('method');?></div></th>
								<th><div><?php echo get_phrase('amount');?></div></th>
								<th><div><?php echo get_phrase('date');?></div></th>
							</tr>
						</thead>
                    <tbody>
                    	<?php 
                            $count = 1;
                            // Fetch currency symbol once before the loop
                            $currency_symbol = $this->db->get_where('settings', array('type' => 'currency'))->row()->description;
                            foreach($payments as $key => $row):
                        ?>
                        <tr>
							<td><?php echo $count++;?></td>
							<td><?php echo $row['title'];?></td>
							<td><?php echo $row['description'];?></td>
							<td>
								<?php 
                                    if ($row['method'] == 1) {
                                        echo get_phrase('card');
                                    } else if ($row['method'] == 2) {
                                        echo get_phrase('cash');
                                    } else if ($row['method'] == 3) {
                                        echo get_phrase('check');
                                    } else if ($row['method'] == 'paypal'){
										echo get_phrase('paypal');
									} else {
                                        echo get_phrase('unknown');
                                    }
                                ?>
							</td>
							<td>
							 <?php echo '₹'; // Temporarily hardcoded ?><?php echo number_format($row['amount'],2,".",",");?>							
							</td>
							<td><?php echo date('d M, Y', $row['timestamp']);?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>