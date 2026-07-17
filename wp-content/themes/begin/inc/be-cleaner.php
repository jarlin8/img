<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// 清理冗余
add_action( 'admin_menu', 'be_cleaner_admin' );
function be_cleaner_admin() {
	add_management_page(
		'清理冗余',
		'<span class="bem"></span>清理冗余',
		'manage_options',
		'be_cleaner',
		'be_cleaner_page'
	);
}

function be_cleaner_page() {
	function be_cleaner( $type ) {
		global $wpdb;
		switch( $type ) {
			case "revision":
				$ru_sql = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
				$wpdb->query($ru_sql);
				break;
			case "draft":
				$ru_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'draft'";
				$wpdb->query($ru_sql);
				break;
			case "autodraft":
				$ru_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
				$wpdb->query($ru_sql);
				break;
			case "moderated":
				$ru_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
				$wpdb->query($ru_sql);
				break;
			case "spam":
				$ru_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
				$wpdb->query($ru_sql);
				break;
			case "trash":
				$ru_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'";
				$wpdb->query($ru_sql);
				break;
			case "postmeta":
				$ru_sql = "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
				$wpdb->query($ru_sql);
				break;
			case "commentmeta":
				$ru_sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
				$wpdb->query($ru_sql);
				break;
			case "relationships":
				$ru_sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
				$wpdb->query($ru_sql);
				break;
			case "feed":
				$ru_sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
				$wpdb->query($ru_sql);
				break;
		}
	}

	function be_cleaner_count($type){
		global $wpdb;
		switch($type){
			case "revision":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "draft":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "autodraft":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "moderated":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "spam":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "trash":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'trash'";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "postmeta":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "commentmeta":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "relationships":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
				$count = $wpdb->get_var($ru_sql);
				break;
			case "feed":
				$ru_sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
				$count = $wpdb->get_var($ru_sql);
				break;
		}
		return $count;
	}

	function be_cleaner_optimize(){
		global $wpdb;
		$ru_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
		$result = $wpdb->get_results($ru_sql);
		foreach($result as $row){
			$ru_sql = 'OPTIMIZE TABLE '.$row->Name;
			$wpdb->query($ru_sql);
		}
	}

		$ru_message = '';

		if(isset($_POST['be_cleaner_revision'])){
			be_cleaner('revision');
			$ru_message = "所有修订已删除";
		}

		if(isset($_POST['be_cleaner_draft'])){
			be_cleaner('draft');
			$ru_message = "所有草稿已删除";
		}

		if(isset($_POST['be_cleaner_autodraft'])){
			be_cleaner('autodraft');
			$ru_message = "所有自动草稿已删除";
		}
		
		if(isset($_POST['be_cleaner_moderated'])){
			be_cleaner('moderated');
			$ru_message = "所有待审核评论已删除";
		}

		if(isset($_POST['be_cleaner_spam'])){
			be_cleaner('spam');
			$ru_message = "所有垃圾评论已删除";
		}

		if(isset($_POST['be_cleaner_trash'])){
			be_cleaner('trash');
			$ru_message = "所有垃圾评论已删除";
		}

		if(isset($_POST['be_cleaner_postmeta'])){
			be_cleaner('postmeta');
			$ru_message = "所有无关联的文章信息已删除";
		}

		if(isset($_POST['be_cleaner_commentmeta'])){
			be_cleaner('commentmeta');
			$ru_message = "所有无关联的评论信息已删除";
		}

		if(isset($_POST['be_cleaner_relationships'])){
			be_cleaner('relationships');
			$ru_message = "所有无关联的信息已删除";
		}

		if(isset($_POST['be_cleaner_feed'])){
			be_cleaner('feed');
			$ru_message = "所有仪表盘消息已删除";
		}

		if(isset($_POST['be_cleaner_all'])){
			be_cleaner('revision');
			be_cleaner('draft');
			be_cleaner('autodraft');
			be_cleaner('moderated');
			be_cleaner('spam');
			be_cleaner('trash');
			be_cleaner('postmeta');
			be_cleaner('commentmeta');
			be_cleaner('relationships');
			be_cleaner('feed');
			$ru_message = "所有不需要的数据已删除";
		}

		if($ru_message != ''){
			echo '<div id="message" class="updated"><p><strong>' . $ru_message . '</strong></p></div>';
		}
	?>

	<div class="wrap">
		<h2 class="bezm-settings">清理冗余</h2>
		<p>用于清理冗余的数据，操作不可逆，请提前作好备份！</p>
		<table class="widefat">
			<thead>
				<tr>
					<th style="width: 20%;">类型</th>
					<th style="width: 5%;">计数</th>
					<th style="width: 10%;">操作</th>
					<th style="width: 50%;">备注：未特殊说明的可以放心删除</th>
				</tr>
			</thead>
			<tbody id="the-list">
				<tr class="alternate">
					<td class="column-name">修订</td>
					<td class="column-name"><?php echo be_cleaner_count('revision'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_revision" value="revision" />
							<?php if ( be_cleaner_count( 'revision' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr>
					<td class="column-name">草稿</td>
					<td class="column-name"><?php echo be_cleaner_count('draft'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_draft" value="draft" />
							<?php if ( be_cleaner_count( 'draft' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name">酌情删除</td>
				</tr>
				<tr class="alternate">
					<td class="column-name">自动草稿</td>
					<td class="column-name"><?php echo be_cleaner_count('autodraft'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_autodraft" value="autodraft" />
							<?php if ( be_cleaner_count( 'autodraft' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr>
					<td class="column-name">待审核评论</td>
					<td class="column-name"><?php echo be_cleaner_count('moderated'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_moderated" value="moderated" />
							<?php if ( be_cleaner_count( 'moderated' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name">酌情删除</td>
				</tr>
				<tr class="alternate">
					<td class="column-name">垃圾评论</td>
					<td class="column-name"><?php echo be_cleaner_count('spam'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_spam" value="spam" />
							<?php if ( be_cleaner_count( 'spam' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr>
					<td class="column-name">垃圾邮件评论</td>
					<td class="column-name"><?php echo be_cleaner_count('trash'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_trash" value="trash" />
							<?php if ( be_cleaner_count( 'trash' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr class="alternate">
					<td class="column-name">无关联的文章信息</td>
					<td class="column-name">
						<?php echo be_cleaner_count('postmeta'); ?>
					</td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_postmeta" value="postmeta" />
							<?php if ( be_cleaner_count( 'postmeta' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr>
					<td class="column-name">无关联的评论信息</td>
					<td class="column-name">
						<?php echo be_cleaner_count('commentmeta'); ?>
					</td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_commentmeta" value="commentmeta" />
							<?php if ( be_cleaner_count( 'commentmeta' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr class="alternate">
					<td class="column-name">无关联的信息</td>
					<td class="column-name">
						<?php echo be_cleaner_count('relationships'); ?>
					</td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_relationships" value="relationships" />
							<?php if ( be_cleaner_count( 'relationships' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
				<tr>
					<td class="column-name">仪表盘消息</td>
					<td class="column-name"><?php echo be_cleaner_count('feed'); ?></td>
					<td class="column-name">
						<form action="" method="post">
							<input type="hidden" name="be_cleaner_feed" value="feed" />
							<?php if ( be_cleaner_count( 'feed' ) > 0 ) { ?>
								<input type="submit" class="button-primary" value="删除" />
							<?php } ?>
						</form>
					</td>
					<td class="column-name"></td>
				</tr>
			</tbody>
		</table>
		</p>
		<p>主题选项 → 基本设置 → 功能优化，关闭“清理冗余”，禁用此功能</p>
	</div>
	<?php
}