<template>
  <div>
    <h1>Artworks</h1>
    <div v-for="artwork in artworks" :key="artwork.id" class="artwork">
      <h2>{{ artwork.title }}</h2>
      <p>{{ artwork.description }}</p>
      <img :src="`/storage/${artwork.image_path}`" alt="Artwork Image" />
      <div>
        <h3>Comments</h3>
        <div v-for="comment in artwork.comments" :key="comment.id" class="comment">
          <p><strong>{{ comment.user.name }}</strong>: {{ comment.comment }}</p>
        </div>
        <textarea v-model="newComment" placeholder="Leave a comment"></textarea>
        <button @click="addComment(artwork.id)">Submit</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Artworks',
  data() {
    return {
      artworks: [],
      newComment: '',
    };
  },
  methods: {
    async fetchArtworks() {
      try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/artworks/show', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        this.artworks = response.data;
      } catch (error) {
        console.error('Error fetching artworks:', error);
      }
    },
    async addComment(artworkId) {
      if (this.newComment.trim() === '') return;
      try {
        const token = localStorage.getItem('token');
        const response = await axios.post(`/api/artworks/${artworkId}/comment`, {
          comment: this.newComment,
        }, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        const artwork = this.artworks.find((a) => a.id === artworkId);
        artwork.comments.push(response.data);
        this.newComment = '';
      } catch (error) {
        console.error('Error adding comment:', error);
      }
    },
  },
  mounted() {
    this.fetchArtworks();
  },
};
</script>

<style scoped>
.artwork {
  border: 1px solid #ccc;
  margin-bottom: 20px;
  padding: 20px;
}

.comment {
  margin-top: 10px;
}
</style>
