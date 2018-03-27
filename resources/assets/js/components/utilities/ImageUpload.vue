<template>
<div>
	<div class="file is-warning" v-show="!image">
	  <label class="file-label">
	    <input class="file-input" type="file" :name="name" @change="onFileChange" ref="fileupload" v-validate="validation">
	    <span class="file-cta">
	      <span class="file-icon">
	        <i class="fas fa-upload"></i>
	      </span>
	      <span class="file-label">
	        Choose a file…
	      </span>
	    </span>
	  </label>
	</div>
	<div v-show="image">
		<img :src="image" :width="width" /><br>
		<a class="button is-danger" @click="removeImage">Remove image</a>
	</div>
</div>
</template>

<script>
import VeeValidate from 'vee-validate';
export default {
  inject: ['$validator'],
  props:['name', 'width', 'validation'],
  data() { 
  	return {
    	image: ''
	  }
  },
  methods: {
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]); 

    },
    createImage(file) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this;

      reader.onload = (e) => {
        vm.image = e.target.result;
        this.$emit('updateImage', vm.image)
      };
      reader.readAsDataURL(file);      
    },
    removeImage: function (e) {
      this.image = '';
      const input = this.$refs.fileupload;
        input.type = 'text';
        input.type = 'file';
      this.$emit('updateImage', this.image)
    }
  }
}
</script>