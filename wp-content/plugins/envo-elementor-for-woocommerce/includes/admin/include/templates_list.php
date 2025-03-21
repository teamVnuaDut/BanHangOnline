<div class="httemplates-templates-area">

    <!-- PopUp Content Start -->
    <div id="etwwpt-popup-area" style="display: none;">
        <div class="httemplate-popupcontent">
            <div class='etwwptspinner'></div>
            <div class="etwwptmessage" style="display: none;">
                <p></p>
                <span class="etwwpt-edit"></span>
            </div>
            <div class="etwwptpopupcontent">
                <ul class="etwwptemplata-requiredplugins"></ul>
				<div class="etww-imports">
					<p><?php esc_html_e('Import template to your Elementor Templates Library', 'etww'); ?></p>
					<span class="etwwptimport-button-dynamic"></span>
					<div class="etwwptpageimportarea">
						<p> <?php esc_html_e('Create a new page from this template', 'etww'); ?></p>
						<input id="etwwptpagetitle" type="text" name="htpagetitle" placeholder="<?php echo esc_attr_x('Enter a Page Name', 'placeholder', 'etww'); ?>">
						<select name="template" id="etwwpttemplates">
							<option value=""><?php esc_html_e('Select Page Template', 'etww'); ?></option>
							<?php 
							$templates = wp_get_theme()->get_page_templates();
							foreach ($templates as $key => $val) {?>
								<option value="<?php esc_attr_e($key); ?>"> <?php esc_html_e($val) ?> </option>
							<?php } ?>
						</select>
						<select name="status" id="etwwptstatus">
							<option value="draft"><?php esc_html_e('Draft', 'etww'); ?></option>
							<option value="publish"><?php esc_html_e('Publish', 'etww'); ?></option>
						</select>
						<?php wp_nonce_field('etww_demo_data_nonce', 'etww_demo_data_nonce'); ?>
						<span class="etwwptimport-button-dynamic-page"></span>
					</div>
				</div>
            </div>
        </div>
    </div>
    <!-- PopUp Content End -->

    <div id="etwwpt-search-section" class="etwwpt-search-section section">
        <div class="container-fluid">
            <form action="#" class="etwwpt-search-form">
                <div class="row">

                    <div class="col-md-auto col">
                        <div class="etwwpt-demos-select">
                            <select id="etwwpt-demos">
                                <option value="templates"><?php esc_html_e('Templates', 'etww'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-auto col">
                        <div class="etwwpt-builder-select">
                            <select id="etwwpt-builder">
                                <option value="all"><?php esc_html_e('All Builders', 'etww'); ?></option>
                                <option value="elementor"><?php esc_html_e('Elementor', 'etww'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto mr-auto">
                        <input id="etwwpt-search-field" type="text" placeholder="<?php esc_attr_e('Search..', 'etww'); ?>">
                    </div>
                    <div class="col-auto">
                        <div class="etwwpt-type-select">
                            <select id="etwwpt-type">
                                <option value="all"><?php esc_html_e('ALL', 'etww'); ?></option>
                                <option value="free"><?php esc_html_e('Free', 'etww'); ?></option>
                                <option value="pro"><?php esc_html_e('Pro', 'etww'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="etwwpt-project-section" class="etwwpt-project-section section">
        <div id="etwwpt-project-grid" class="etwwpt-project-grid row" style="overflow: hidden;">
            <h2 class="etwwpt-project-message"><span class="etwwpt-pro-loading2"></span></h2>
        </div>
        <div id="etwwpt-load-more-project" class="text-center"></div>
    </div>

    <div id="etwwpt-group-section">
        <div id="etwwpt-group-bar" class="etwwpt-group-bar">
            <span id="etwwpt-group-close" class="back"><i>&#8592;</i> <?php esc_html_e('Back to Library', 'etww'); ?></span>
            <h3 id="etwwpt-group-name" class="title"></h3>
        </div>

        <div id="etwwpt-group-grid" class="row"></div>
        <a href="#top" class="etwwpt-groupScrollToTop"><?php echo esc_html__('Top', 'etww'); ?></a>
    </div>

    <a href="#top" class="etwwpt-scrollToTop"><?php echo esc_html__('Top', 'etww'); ?></a>

</div>