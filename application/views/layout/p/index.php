<section id="team" class="section section-hilite section-team loaded">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2 class="section-title">
					<span>
						<?php $user_id=$this->
							session->userdata('user_id'); $provider = $this->session->userdata('provider'); if(!empty($title)): echo $title; endif;?>
					</span>
				</h2>
				<div class="entry-content">
					<p>
						<?php echo @$page_content;?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>