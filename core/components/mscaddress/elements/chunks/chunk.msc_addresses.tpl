<div id="mscaAddress">
	<form method="post" class="msca_form">
		<button class="btn btn-default" type="submit" name="msca_action" value="address/add">
			<i class="glyphicon glyphicon-plus"></i>
		</button>
	</form>
	<br>
    {if !count($addresses)}
        {'msca_addr_is_empty' | lexicon}
    {else}
		<div class="table-responsive">
            <table class="table table-striped">
                <tr class="header">
                    <th class="title">{'msca_addr_title' | lexicon}</th>
                    <th class="city">{'ms2_frontend_city' | lexicon}</th>
                    <th class="street">{'ms2_frontend_street' | lexicon}</th>
                    <th class="building">{'ms2_frontend_building' | lexicon}</th>
                    <th class="room">{'ms2_frontend_room' | lexicon}</th>
                    <th class="edit">{'msca_addr_edit' | lexicon}</th>
                    <th class="remove">{'msca_addr_remove' | lexicon}</th>
                </tr>
				{foreach $addresses as $address}
					<tr id="msca_addr_{$address.id}" class="">
						{foreach ['title','city','street','building','room'] as $field}
							<td class="{$field}">
								{$address[$field]}
							</td>
						{/foreach}
						<td class="edit">
							<form method="post" class="msca_form">
								<input type="hidden" name="id" value="{$address.id}">
								<button class="btn btn-default" type="submit" name="msca_action" value="address/edit">
									<i class="glyphicon glyphicon-pencil"></i>
								</button>
							</form>
						</td>
						<td class="remove">
							<form method="post" class="msca_form">
								<input type="hidden" name="id" value="{$address.id}">
								<button class="btn btn-default" type="submit" name="msca_action" value="address/remove">
									<i class="glyphicon glyphicon-remove"></i>
								</button>
							</form>
						</td>
					</tr>
				{/foreach}
            </table>
        </div>
    {/if}
	{$form}
</div>
