<form class="form-horizontal msca_form" id="mscaForm" method="post">
	{if $form['id']}
		<input type="hidden" name="id" value="{$form['id']}" />
	{/if}
	<div class="row">
        <div class="col-md-6">
            <div class="form-group input-parent">
                <label class="col-md-4 control-label" for="addr_title">
                    <span class="required-star">*</span> {'msca_addr_title' | lexicon}</label>
                <div class="col-sm-6">
                    <input type="text" id="addr_title" placeholder="{'msca_addr_title' | lexicon}"
                               name="title" value="{$form['title']}"
                               class="form-control{($errors['title']) ? ' error' : ''}">
					<span class="error_title"></span>
                </div>
            </div>
        </div>
	</div>
    <div class="row">
        <div class="col-md-6">
            <h4>{'ms2_frontend_credentials' | lexicon}:</h4>
            {foreach ['receiver','phone','email'] as $field}
                <div class="form-group input-parent">
                    <label class="col-md-4 control-label" for="{$field}">
						{if $field in list $requires}
							<span class="required-star">*</span>
						{/if}
                        {('ms2_frontend_' ~ $field) | lexicon}
                    </label>
                    <div class="col-sm-6">
                        <input type="text" id="{$field}" placeholder="{('ms2_frontend_' ~ $field) | lexicon}"
                               name="{$field}" value="{$form[$field]}"
                               class="form-control{($errors[$field]) ? ' error' : ''}">
						<span class="error_{$field}"></span>
                    </div>
                </div>
            {/foreach}

            <div class="form-group input-parent">
                <label class="col-md-4 control-label" for="comment">
					{if 'comment' in list $requires}
						<span class="required-star">*</span>
					{/if}
                    {'ms2_frontend_comment' | lexicon}</label>
                <div class="col-sm-6">
                    <textarea name="comment" id="comment" placeholder="{'ms2_frontend_comment' | lexicon}"
                              class="form-control{($errors['comment']) ? ' error' : ''}">{$form.comment}</textarea>
					<span class="error_comment"></span>
                </div>
            </div>
        </div>
		
        <div class="col-md-6">
            <h4>{'ms2_frontend_address' | lexicon}:</h4>
            {foreach ['index','region','city'] as $field}
                <div class="form-group input-parent">
                    <label class="col-md-4 control-label" for="{$field}">
						{if $field in list $requires}
							<span class="required-star">*</span>
						{/if}
                        {('ms2_frontend_' ~ $field) | lexicon}
                    </label>
                    <div class="col-sm-6">
                        <input type="text" id="{$field}" placeholder="{('ms2_frontend_' ~ $field) | lexicon}"
                               name="{$field}" value="{$form[$field]}"
                               class="form-control{($errors[$field]) ? ' error' : ''}">
						<span class="error_{$field}"></span>
                    </div>
                </div>
            {/foreach}
            <div class="form-group input-parent">
                <label class="col-md-4 control-label" for="street">
                    {'ms2_frontend_street' | lexicon}</label>
                <div class="col-md-6">
                    <div class="row mb-2">
                        {foreach ['street','building','room'] as $field}
                            <div class="col-md-4">
                                <input type="text" id="{$field}" placeholder="{('ms2_frontend_' ~ $field) | lexicon}"
                                    name="{$field}" value="{$form[$field]}"
                                    class="form-control{($errors[$field]) ? ' error' : ''}">
                                <span class="error_{$field}"></span>
                            </div>
                        {/foreach}
                        {foreach ['entrance','floor'] as $field}
                            <div class="col-md-6 mt-2">
                                <input type="text" id="{$field}" placeholder="{('ms2_frontend_' ~ $field) | lexicon}"
                                    name="{$field}" value="{$form[$field]}"
                                    class="form-control{($errors[$field]) ? ' error' : ''}">
                                <span class="error_{$field}"></span>
                            </div>
                        {/foreach}
                    </div>

                    <textarea name="text_address" id="text_address" placeholder="{'ms2_frontend_text_address' | lexicon}"
                    class="form-control{($errors['text_address']) ? ' error' : ''}">{$form.text_address}</textarea>
                    <span class="error_text_address"></span>
                </div>
            </div>
        </div>

        <div class="col-md-offset-2">
            <button type="submit" name="msca_action" value="address/save" class="btn btn-default btn-primary">
                {'msca_addr_save' | lexicon}
            </button>
        </div>
    </div>
</form>