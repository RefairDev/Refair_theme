(n=>{function t(){var t=_wpCustomizeSettings.controls[n(this).attr("id")].auroobamakes_tinymce_toolbar1,i=_wpCustomizeSettings.controls[n(this).attr("id")].auroobamakes_tinymce_toolbar2,o=_wpCustomizeSettings.controls[n(this).attr("id")].auroobamakes_tinymce_mediabuttons,e=_wpCustomizeSettings.controls[n(this).attr("id")].auroobamakes_tinymce_height;wp.editor.initialize(n(this).attr("id"),{tinymce:{wpautop:!0,toolbar1:t,toolbar2:i,height:e},quicktags:!0,mediaButtons:o})}function i(t,i){i.on("change",function(){tinyMCE.triggerSave(),n("#".concat(i.id)).trigger("change")})}n(document).on("ready",function(){n(document).on("tinymce-editor-init",i),wp&&wp.hasOwnProperty("customize")&&wp.customize.bind("ready",function(){n(".customize-control-tinymce-editor").each(t)})})})(jQuery,wp.customize);
//# sourceMappingURL=scripts_admin.js.map