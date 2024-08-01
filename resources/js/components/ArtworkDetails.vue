<template>
  <div v-if="artwork">
    <h1>{{ artwork.title }}</h1>
    <p>{{ artwork.description }}</p>
    <img :src="`/storage/${artwork.image_path}`" alt="Artwork Image" />
    <div>
      <h3>Comments</h3>
      <div v-for="comment in artwork.comments" :key="comment.id" class="comment">
        <p><strong>{{ comment.name }}</strong>: {{ comment.comment }}</p> <!-- Use comment.name -->
      </div>
      <textarea v-model="newComment" placeholder="Leave a comment"></textarea>
      <button @click="addComment">Submit</button>
    </div>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ArtworkDetails',
  data() {
    return {
      artwork: null,
      newComment: '',
    };
  },
  methods: {
    async fetchArtwork() {
      try {
        const token = localStorage.getItem('token');
        const response = await axios.get(`/api/artworks/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        this.artwork = response.data;
      } catch (error) {
        console.error('Error fetching artwork details:', error);
      }
    },
    async addComment() {
      if (this.newComment.trim() === '') return;
      try {
        const token = localStorage.getItem('token');
        const response = await axios.post(`/api/artworks/${this.artwork.id}/comment`, {
          comment: this.newComment,
        }, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        
        // Update the comments array immediately
        this.artwork.comments.push(response.data);
        
        // Clear the new comment input field
        this.newComment = '';
      } catch (error) {
        console.error('Error adding comment:', error);
      }
    },
  },
  mounted() {
    this.fetchArtwork();
  },
};
</script>

<style scoped>
.comment {
  margin-top: 10px;
}
</style>
