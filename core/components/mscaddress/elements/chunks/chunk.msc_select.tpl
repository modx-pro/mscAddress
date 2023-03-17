{if count($addresses)}
    <div class="form-group input-parent">
        <label class="col-md-4 control-label" for="msca_address">
            {'msca_addr_order' | lexicon}
        </label>
        <div class="col-sm-6">
            <select class="form-control" id="msca_address" name="msca_address">
        		<option value="">{'msca_addr_select' | lexicon}</option>
        	{foreach $addresses as $address}
        		<option value="{$address.id}" {($address.id == $selected) ? 'selected' : ''}>{$address.title}</option>
        	{/foreach}
        	</select>
        </div>
    </div>
{/if}