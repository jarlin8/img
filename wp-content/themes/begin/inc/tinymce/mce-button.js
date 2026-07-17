(function() {
	tinymce.PluginManager.add('begin_mce_button', function( editor, url ) {
		editor.addButton( 'begin_mce_button', {
			text: '[/] 短代码',
			icon: '',
			title : false,
			classes: 'bacode-btn',
			type: 'menubutton',
			menu: [

				{
					text: '内容保护',
					menu: [
						{
							text: '密码保护',
							icon: 'lock',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[password key="密码"]'+selected+'[/password]');
							}
						},

						{
							text: '微信验证',
							icon: 'unlock',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[wechat key="验证码" reply="回复关键字"]'+selected+'[/wechat]');
							}
						},

						{
							text: '回复可见',
							icon: 'bubble',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[reply title="标题" explain="说明"]'+selected+'[/reply]');
							}
						},

						{
							text: '登录可见',
							icon: 'user',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[login title="" explain=""]'+selected+'[/login]');
							}
						},

						{
							text: '会员可见',
							icon: 'preview',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[hide title="隐藏的内容" explain="仅会员可见" role="无权查看" tip="升级为会员可见"]'+selected+'[/hide]');
							}
						},

						{
							text: '登录保护',
							icon: 'contrast',
							onclick: function() {
								editor.insertContent('[bepassword key="密码-仅用于“用户注册页面”模板"][beregister][/bepassword]');
							}
						},
					]
				},

				{
					text: '链接按钮',
					menu: [
						{
							text: '多栏按钮盒子',
							icon: 'pluscircle',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[bebtn]'+selected+'[/bebtn]');
							}
						},

						{
							text: '下载按钮',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[url href="' + '下载链接地址"]按钮名称[/url]');
							}
						},

						{
							text: '链接按钮',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[link href="' + '链接地址"]按钮名称[/link]');
							}
						},

						{
							text: '居中按钮',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[but href="' + '链接地址"]居中按钮[/but]');
							}
						},

						{
							text: '弹窗下载',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[button]文件下载[/button]');
							}
						},

						{
							text: '直达按钮',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[go]' + '');
							}
						},

						{
							text: '弹窗链接',
							icon: 'fullscreen',
							onclick: function() {
								editor.insertContent('[beiframe href="' + '链接地址"]按钮名称[/beiframe]');
							}
						},

					]
				},

				{
					text: '图片相关',
					menu: [
						{
							text: '添加幻灯',
							icon: 'image',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[img]'+selected+'[/img]');
							}
						},

						{
							text: '图片滑块',
							icon: 'image',
							classes: 'ba-mce-btn',
						},

						{
							text: '添加宽图',
							icon: 'image',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[full_img]'+selected+'[/full_img]');
							}
						},

						{
							text: '隐藏图片',
							icon: 'image',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[hide_img]'+selected+'[/hide_img]');
							}
						},
					]
				},

				{
					text: '装饰美化',
					icon: '',
					menu: [

						{
							text: '禁止解析',
							icon: 'removeformat',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[ban]'+selected+'[/ban]');
							}
						},

						{
							text: '联系表单',
							icon: 'bubble',
							onclick: function() {
								editor.insertContent('[be_mail_contact_form]');
							}
						},


						{
							text: '插入章节',
							icon: 'line',
							onclick: function() {
								editor.insertContent('[chapter text="章节"]' + '');
							}
						},

						{
							text: '选项卡',
							icon: 'template',
							onclick: function() {
								editor.insertContent('<p>[start_tab]</p><p>[wptab title="标题"]' + '添加内容' + '[/wptab]</p><p>[wptab title="标题"]' + '添加内容' + '[/wptab]</p><p>[end_tab]');
							}
						},

						{
							text: '标题序号',
							icon: 'numlist',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[subhead]'+selected+'[/subhead]');
							}
						},

						{
							text: '首字下沉',
							icon: 'forecolor',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[drop]'+selected+'[/drop]');
							}
						},

						{
							text: '中英混排',
							icon: 'spellchecker',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[en]'+selected+'[/en]');
							}
						},


						{
							text: '两栏文字',
							icon: 'tabledeletecol',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[two_column]'+selected+'[/two_column]');
							}
						},

						{
							text: '文字居中',
							icon: 'aligncenter',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[align center="1"]'+selected+'[/align]');
							}
						},

						{
							text: '文字折叠',
							icon: 'pluscircle',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[s title="小标题"][p]<p>'+selected+'</p>' + '<p>[/p]</p>');
							}
						},

						{
							text: '时间轴',
							icon: 'insertdatetime',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[line]'+selected+'[/line]');
							}
						},

						{
							text: '浅色文字',
							icon: 'forecolor',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[bec]'+selected+'[/bec]');
							}
						},

						{
							text: '字体图标',
							icon: 'sharpen',
							onclick: function() {
								editor.insertContent('[fontzm icon="图标代码" size="24" color="c40000" sup="0"]');
							}
						},

						{
							text: '固定提示框',
							icon: 'warning',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[docs]'+selected+'[/docs]');
							}
						},

					]
				},

				{
					text: '嵌入内容',
					menu: [

						{
							text: '登录按钮',
							icon: 'user',
							onclick: function() {
								editor.insertContent('[loginbut sup="0"]登录[/loginbut]');
							}
						},

						{
							text: '复制按钮',
							icon: 'paste',
							onclick: function() {
								editor.insertContent('[copy tip="0"]文字内容[/copy]');
							}
						},

						{
							text: '嵌入文章',
							icon: 'newdocument',
							onclick: function() {
								editor.insertContent('[quote ids="文章ID"]' + '');
							}
						},

						{
							text: '添加视频',
							icon: 'media',
							onclick: function() {
								editor.windowManager.open({
									title: '添加视频',
									file: url + '/insert-video.php',
									width: 500,
									height: 420,
									inline: 1
								},
								{
									plugin_url: url
								});
							}
						},

						{
							text: '图文模块',
							icon: 'gamma',
							onclick: function() {
								editor.windowManager.open({
									title: '添加一个图文模块',
									file: url + '/add-list.php',
									width: 500,
									height: 400,
									inline: 1
								},
								{
									plugin_url: url
								});
							}
						},

						{
							text: '左右图文',
							icon: 'copy',
							onclick: function() {
								editor.insertContent('[be_text_img align="left" title="标题" text="说明" img="图片链接"]' + '');
							}
						},

						{
							text: 'Ajax分类',
							icon: 'tableleftheader',
							onclick: function() {
								editor.insertContent('[be_ajax_post terms="1,2,3" posts_per_page="8" column="4" btn_all="no"]' + '');
							}
						},

						{
							text: '计数器',
							icon: 'insertdatetime',
							onclick: function() {
								editor.insertContent('[counter id="1" title="计数器标题" value="数值" speed="40000" ico="be be-eye" n="1"]');
							}
						},

						{
							text: '同标签文章',
							icon: 'anchor',
							onclick: function() {
								editor.insertContent('[tags_post title="小标题" n="篇数" ids="标签ID"]' + '');
							}
						},

						{
							text: 'Fieldset标签',
							icon: 'template',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('<fieldset><legend>我是标题</legend>'+selected+'</fieldset>' + '');
							}
						},

						{
							text: 'Details标签',
							icon: 'pluscircle',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[details title="小标题" open="0"]'+selected+'[/details]' + '');
							}
						},

						{
							text: 'Iframe标签',
							icon: 'tablerowprops',
							onclick: function() {
								editor.insertContent('[iframe src="网址"' + ']');
							}
						},

						{
							text: '插入广告',
							icon: 'upload',
							onclick: function() {
								editor.insertContent('[ad]' + '');
							}
						},

						{
							text: 'MP4视频',
							icon: 'media',
							onclick: function() {
								editor.insertContent('[videos src="视频地址"]');
							}
						}
					]
				},

				{
					text: '页面构建',
					menu: [

						{
							text: '分类模块',
							icon: 'books',
							menu: [
								{
									text: '分类组合',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[portfolio id="分类ID"]');
									}
								},

								{
									text: '热门分类',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[hotcat id="分类ID"]');
									}
								},

								{
									text: '图片模块',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[picture id="1" number="4" col="4"]');
									}
								},

								{
									text: '软件下载',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[catdown id="1" number="3" col="3" btn="按钮"]');
									}
								},

								{
									text: '软件文章',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[postdown id="1,2,3" col="3" btn="按钮"]');
									}
								},

								{
									text: '会员商品',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[assets id="1" number="5" col="5"]');
									}
								},

								{
									text: '小说书籍',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[novel id="1,2,3" mark="1" marktext="角标文字"]');
									}
								},

								{
									text: '分类列表',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[catlist id="1,2" number="5"]');
									}
								},

								{
									text: '热门推荐',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[carousel id="1" number="8" title="标题" des="说明" img="图片"]');
									}
								},

								{
									text: '分类封面',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[cover id="1,2,3,4" col="4" ico="1"]');
									}
								},

								{
									text: '首页幻灯',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[slider]');
									}
								},

								{
									text: '分类图片',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[square id="1" number="12"]');
									}
								},

								{
									text: '热门文章',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[boxhot]<p>[hotpost id="90,94,96,146" title="标题" ico="be be-skyatlas" url="#" col="4"]</p>[/boxhot]');
									}
								},

							]
						},

						{
							text: '组合模块',
							icon: 'books',
							menu: [

								{
									text: '关于我们',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[contact title="标题" col="1" more="详细按钮" moreurl="详细链接" contact="关于按钮" contacturl="关于链接" img="图片" des="说明描述"]');
									}
								},

								{
									text: '展望模块',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[outlook title="标题" des="描述" img="背景图片" btn="按钮文字1" url="按钮链接1" more="按钮文字2" link="按钮链接2"]');
									}
								},

								{
									text: '联系表单',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[be_mail_contact_form]');
									}
								},

								{
									text: '特色模块',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[boxmd col="6"]<p>[md title="标题" text="描述" col="6" ico="be be-skyatlas" svg="" img=""]</p>[/boxmd]');
									}
								},

								{
									text: '工具模块',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[boxtool]<p>[tool title="标题" text="描述" col="5" ico="be be-skyatlas" svg="" btn="按钮" btnurl="链接" img="图片"]</p>[/boxtool]');
									}
								},

								{
									text: '支持模块',
									icon: 'wp_page',
									onclick: function() {
										selected = tinyMCE.activeEditor.selection.getContent();
										editor.insertContent('[boxassist]<p>[assist title="标题" text="描述" ico="be be-skyatlas" url=""]</p>[/boxassist]');
									}
								},

							]
						},

						{
							text: '居中盒子',
							icon: 'tablerowprops',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[norm top="0" bottom="0"]<p>'+selected+'</p>[/norm]');
							}
						},

						{
							text: '全宽盒子',
							icon: 'tablerowprops',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[full white="1" top="0" bottom="0"]<p>'+selected+'</p>[/full]');
							}
						},

						{
							text: '两栏盒子',
							icon: 'tablerowprops',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[column top="0" bottom="0"]<p>'+selected+'</p>[/column]');
							}
						},

						{
							text: '多栏盒子',
							icon: 'tablerowprops',
							onclick: function() {
								editor.insertContent('[colstart col="分栏数"]<p>[colitem]' + '添加内容' + '[/colitem]</p><p>[colitem]' + '添加内容' + '[/colitem]</p>[colend]');
							}
						},

						{
							text: '分类标题',
							icon: 'tablerowprops',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[betitle title="标题" url="链接" des="说明"]');
							}
						},

						{
							text: '更多按钮',
							icon: 'wp_more',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[more url="链接" text="提示"]');
							}
						},

					]
				},

				{
					text: '彩色背景',
					menu: [
						{
							text: '绿色框',
							icon: 'fill',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[mark_a]'+selected+'[/mark_a]');
							}
						},

						{
							text: '红色框',
							icon: 'fill',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[mark_b]'+selected+'[/mark_b]');
							}
						},

						{
							text: '灰色框',
							icon: 'fill',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[mark_c]'+selected+'[/mark_c]');
							}
						},

						{
							text: '黄色框',
							icon: 'fill',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[mark_d]'+selected+'[/mark_d]');
							}
						},

						{
							text: '蓝色框',
							icon: 'fill',
							onclick: function() {
								selected = tinyMCE.activeEditor.selection.getContent();
								editor.insertContent('[mark_e]'+selected+'[/mark_e]');
							}
						}
					]
				},

			]
		});
	});
})();