<template>
<div>
	<div class="box is-paddingless">
		<div id="toolbar">
			<p class="field">
			  <a class="button is-dark is-small" @click="addMarkdown('bold')" alt="Bold">
			    <span class="icon is-small">
			      <i class="fas fa-bold"></i>
			    </span>
			  </a>
			  <a class="button is-dark is-small" @click="addMarkdown('italic')">
			    <span class="icon is-small">
			      <i class="fas fa-italic"></i>
			    </span>
			  </a>
<!-- 			  <a class="button is-dark is-small" @click="addMarkdown('underline')">
			    <span class="icon is-small">
			      <i class="fas fa-underline"></i>
			    </span>
			  </a> -->
			  <a class="button is-dark is-small" @click="addMarkdown('strikethrough')">
			    <span class="icon is-small">
			      <i class="fas fa-strikethrough"></i>
			    </span>
			  </a>
			  <span class="divider">|</span>
			  <a class="button is-dark is-small" @click="addMarkdown('link')">
			    <span class="icon is-small">
			      <i class="fas fa-link"></i>
			    </span>
			  </a>
			  <a class="button is-dark is-small" @click="addMarkdown('image')">
			    <span class="icon is-small">
			      <i class="fas fa-image"></i>
			    </span>
			  </a>
			  <a class="button is-dark is-small" @click="addMarkdown('youtube')">
			    <span class="icon is-small">
			      <i class="fab fa-youtube"></i>
			    </span>
			  </a>
			  <span class="divider">|</span>
			  <a class="button is-dark is-small" @click="addMarkdown('quote')">
			    <span class="icon is-small">
			      <i class="fas fa-quote-left"></i>
			    </span>
			  </a>
			  <a class="button is-dark is-small" @click="addMarkdown('code')">
			    <span class="icon is-small">
			      <i class="fas fa-code"></i>
			    </span>
			  </a>
			  <span class="divider">|</span>
			  <a class="button is-dark is-small" @click="addMarkdown('heading')">
			    <span class="icon is-small">
			      <i class="fas fa-heading"></i>
			    </span>
			  </a>
<!-- 			  <a class="button is-dark is-small" @click="addMarkdown('center')">
			    <span class="icon is-small">
			      <i class="fas fa-align-center"></i>
			    </span>
			  </a> -->
			  <a class="button is-dark is-small" @click="addMarkdown('listUL')">
			    <span class="icon is-small">
			      <i class="fas fa-list-ul"></i>
			    </span>
			  </a>
			  <a class="button is-dark is-small" @click="addMarkdown('listOL')">
			    <span class="icon is-small">
			      <i class="fas fa-list-ol"></i>
			    </span>
			  </a>
			  <span class="divider">|</span>
			  <a class="button is-light is-small" @click="preview=!preview">
            <b-icon
								v-if="preview"
                icon="eye"
                size="is-small">
            </b-icon>
			    <span class="icon is-small" v-else>
			      <i class="fas fa-eye-slash"></i>
			    </span>
			  </a>
			</p>
		</div>
			<div class="columns is-gapless is-paddingless" id="editor" style="min-height: 250px;">
			  <div class="column">
			    <textarea :value="value" @input="update" ref="input" class="autoExpand" rows="10" data-min-rows="10" ></textarea>
			  </div>
			  <div class="column" v-if="preview">
			    <div v-html="compiledMarkdown" class="content" id="parsed"></div>
			  </div>
			</div>
	</div>
</div>
</template>

<script>
var md = require('markdown-it')();
window._ = require('lodash');

export default {
  props: ['value'],
  data() {
  	return {
  		preview: true,

  		styles: {
  			'bold': { prefix: '**', subfix: '**' },
  			'italic': { prefix: '*', subfix: '*' },
  			'underline': { prefix: '__', subfix: '__' },
  			'strikethrough': { prefix: '~~', subfix: '~~' },
  			'link': { prefix: "[", subfix: '](http://mythril.io)' },
  			'image': { prefix: "![alt text](", subfix: ')' },
  			'youtube': { prefix: "[![IMAGE ALT TEXT HERE](http://img.youtube.com/vi/YOUTUBE_VIDEO_ID_HERE/0.jpg)](", subfix: ')' },
  			'quote': { prefix: '> ', subfix: '' },
  			'code': { prefix: '```language\n', subfix: '\n```' },
  			'heading': { prefix: '## ', subfix: '' },
  			'center': { prefix: '->', subfix: '<-' },
  			'listUL': { prefix: '* ', subfix: '' },
  			'listOL': { prefix: '1. ', subfix: '' },
  		}
  	}
  },
  computed: {
    compiledMarkdown: function () {
      return md.render(this.value);
    }
  },
  methods: {
    update: _.debounce(function (e) {
      this.$emit('input', e.target.value)
    }, 300),
    addMarkdown(style) {
    	var textArea = this.$refs.input;
        var startPosition = textArea.selectionStart;
        var endPosition = textArea.selectionEnd;
        var type = style;
        // Check if you've selected text
        if(startPosition == endPosition) {
        	textArea.value = textArea.value.substring(0, startPosition) + 
        					  this.styles[type]['prefix'] + this.styles[type]['subfix'] + 
        					  textArea.value.substring(endPosition, textArea.value.length);
        } else {
        	var selectedValue = textArea.value.substring(startPosition, endPosition)
        	textArea.value = textArea.value.substring(0, startPosition) + 
        					  this.styles[type]['prefix'] + selectedValue + this.styles[type]['subfix'] + 
        					  textArea.value.substring(endPosition, textArea.value.length);
        } 
        this.$emit('input', textArea.value)
    }
  }
}
</script>

<style>
#toolbar {
	background-color: #363636;
	padding: 10px;
	border-bottom: 1px solid #ccc;
}
#editor {
	background-color: #f6f6f6;
}

textarea, #parsed {
  display: inline-block;
  width: 100%;
  height: 100%;
  vertical-align: top;
  box-sizing: border-box;
  padding: 20px;
}
textarea {
  border: none;
  border-right: 1px solid #ccc;
  resize: none;
  outline: none;
  font-size: 14px;
  font-family: 'Monaco', courier, monospace;
}

#parsed pre {
	background-color: #363636;
	color: #FFFFFF;
}
</style>