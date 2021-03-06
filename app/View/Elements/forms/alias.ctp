<?php
/**
 * Alias element
 * 
 * Creates a form input for the Alias.name, and Alias.id if available, and the javascript and css to go with it. 
 */
$aliasIdDefaults = array('type' => 'hidden');
$aliasId['Alias.id'] = !empty($aliasId['Alias.id']) ? array_merge($aliasIdDefaults, $aliasId['Alias.id']) : $aliasIdDefaults;
$aliasNameDefaults = array('label' => 'Permanent Url');
$aliasName['Alias.name'] = !empty($aliasId['Alias.name']) ? array_merge($aliasNameDefaults, $aliasId['Alias.name']) : $aliasNameDefaults;
$prefix = !empty($prefix) ? $prefix : '';
$formId = !empty($formId) ? $formId : null;
$defaultInputOptions = array('required' => false, 'label' => 'Permanent Url');
$inputOptions = !empty($inputOptions) && is_array($inputOptions) ? array_merge($defaultInputOptions, $inputOptions) : $defaultInputOptions;
$nameInput = !empty($nameInput) ? $nameInput : null;
$aliasInput = !empty($aliasInput) ? $aliasInput : '#AliasName';
!empty($foreignKey) ? $foreignKey = '' : $foreignKey = !empty($this->request->data['Alias']['value']) ? $this->request->data['Alias']['value'] : '';
$aliasPlugin = !empty($aliasPlugin) ? $aliasPlugin : $this->request->params['plugin'];
$aliasController = !empty($aliasController) ? $aliasController : $this->request->params['controller'];
$aliasAction = !empty($aliasAction) ? $aliasAction : 'view';
$parent = !empty($parent) ? $parent : null;
echo !empty($this->request->data['Alias']['id']) ? $this->Form->input(key($aliasId)) : null;
echo $this->Form->input('Alias.value', array('type' => 'hidden', 'value' => $foreignKey));
echo $this->Form->input('Alias.plugin', array('type' => 'hidden', 'value' => $aliasPlugin));
echo $this->Form->input('Alias.controller', array('type' => 'hidden', 'value' => $aliasController));
echo $this->Form->input('Alias.action', array('type' => 'hidden', 'value' => $aliasAction));
echo $this->Form->input(key($aliasName), $inputOptions); ?>

<style type="text/css">
    #permaLink {
        background: #fff7c9;
    }
</style>

<script type="text/javascript">

(function($) {
    
    var formId = '<?php echo $formId; ?>';
    var aliasId = $("#AliasId");
    var aliasValue = normalize($('<?php echo $nameInput; ?>').val());
    var permaLinkHtml = '<?php echo __('&nbsp;&nbsp; &nbsp;<small><strong>Permalink: </strong>%s/<span id="permaLink" title="Change">%s</span> <a class="btn btn-primary btn-xs" id="permaLinkEdit">Change</a></small>', $_SERVER['HTTP_HOST'], $this->request->data['Alias']['name']); ?>'.replace('></span>', aliasValue + '></span>');
   	var prefix = '<?php echo $prefix; ?>';
   	var newPermaLink = null;
   	   	
    //$('h1.page-title').after(permaLinkHtml);
    console.log(permaLinkHtml);
    $('<?php echo $dataDisplay; ?>').after(permaLinkHtml);
    
    var permaLink = $('<?php echo $aliasInput ?>').val();
    
    $(document).on('click', '#permaLink, #permaLinkEdit', function() {
       var newlink = $('#permaLink').html();
       $('#permaLink').replaceWith('<span class="form-inline" id="aliasForm"><input type="text" value="' + newlink + '" id="slugInput"> <a class="btn btn-primary btn-xs" id="saveSlug">Done</a> <a class="btn btn-primary btn-xs" id="cancelSlug">Cancel</a> <span id="saveOld"></span></span>');
       $('#permaLinkEdit').hide();
    });
    $(document).on('keyup', '#slugInput', function () {
        $("#AliasName").val($(this).val());
        if ($("#AliasId").length > 0 ) {
            $("#saveOld").replaceWith('<a id="saveOldLink" class="btn btn-xs btn-danger" rel="tooltip" title="Click here to keep the old url working, so that links pointing to the old url will not break.">Keep old url live?</a></small>');
        }
        $("a[rel=tooltip]").tooltip();
    });
    $(document).on('click', '#saveOldLink', function () {
        $(".tooltip").remove();
        $("#AliasId").remove();
        $("#saveOldLink").replaceWith('<a id="oldLinkSaved" class="btn btn-xs btn-success" rel="tooltip" title="This means that old links pointing to the old url will still work. If this was a mistake, you will need to refresh the page before saving any changes.">Old url has been preserved! &nbsp;&nbsp; <button type="button" class="close" data-dismiss="alert">×</button></a></small>');
        $("a[rel=tooltip]").tooltip();
    });
    $(document).on('click', '.close', function() {
        $(".tooltip").remove();
    });
    $(document).on('click', '#saveSlug', function () {
        checkAvailability();
    });
    $(document).on('click', '#cancelSlug', function () {
        $(formId).prepend(aliasId); // bring back the alias id in case it was removed with the #saveOldLink button
        $('#aliasForm').replaceWith('<span id="permaLink">' + permaLink + '</span>');
        $('#permaLinkEdit').show();
    });
    
    <?php if (!empty($nameInput)) { ?>
    $(document).on('keyup', '<?php echo $nameInput; ?>', function () {
        <?php if (!empty($parent)) { ?>
        if ($('<?php echo $aliasInput; ?>').val() == '<?php echo $this->request->data['Alias']['name']; ?>') {
            $('#permaLink').html(prefix + '<?php echo $this->request->data['Alias']['name']; ?>' + normalize($(this).val()));
        }
        <?php } else { ?> 
        if (!$('<?php echo $aliasInput; ?>').val()) {
            $('#permaLink').html(prefix + normalize($(this).val()));
        }
        <?php } ?>
    });
    $(document).on('blur', '<?php echo $nameInput; ?>', function () {
        checkAvailability();
    });
    <?php } ?>
    
    $(document).on('keyup', '<?php echo $aliasInput; ?>', function () {
        $(this).val(normalize($(this).val()));
        $('#permaLink').html(normalize($(this).val()));
    });
    $(document).on('blur', '<?php echo $aliasInput; ?>', function () {
        checkAvailability();
    });
    
    $(formId).submit( function() {
        if (!$('<?php echo $aliasInput; ?>').val()) {
            $('<?php echo $aliasInput; ?>').val($('#permaLink').html());
        }
    });
    
    // normalize the url to remove characters we don't want to allow
    function normalize(string) {
    	string = string.replace(/[^a-zA-Z0-9\/]+/g, '-').toLowerCase();
    	if(string.substr(-1) == '-') {
	        return string.substr(0, string.length - 1);
	    } else {
	    	return string;
	    }
    }
    
    function checkAvailability() {
        // check alias availability, append a number at the end if not available
        // right now 11/19/2012 the only failure I see, is in the sub page add it doesn't run a check after the webpage name input is used
        newPermaLink = $('#slugInput').val() ? $('#slugInput').val() : $("#permaLink").html();
	 if(typeof newPermaLink == 'undefined') {
		 newPermaLink = $('<?php echo $aliasInput; ?>').val();
	}
        if (newPermaLink != permaLink) {
            $.getJSON('/aliases/count/' + newPermaLink.replace('/', '\+', 'g') + '.json', 
                function(data) {
                    // if there is a conflict append a number at the end of the alias
                    var conflict = false;
                    if (data.alias) {
                        conflict = true;
                    }
                    if (conflict) {
                        newPermaLink = newPermaLink + data.alias;
                    }
                    // needed here instead of just the bottom because the update doesn't get past here for some reason
                    $("#aliasForm, #permaLink").replaceWith('<span id="permaLink">' + newPermaLink + '</span>');
                    $("#AliasName").val(newPermaLink);
                    $('#permaLinkEdit').show();
                }
            );
        } else {
            $("#aliasForm, #permaLink").replaceWith('<span id="permaLink">' + newPermaLink + '</span>');
            $("#AliasName").val(newPermaLink);
            $('#permaLinkEdit').show();
        }
    }
})(jQuery);
</script>