<template>
  <div>
    <h1>Artworks</h1>
    <div v-for="artwork in artworks" :key="artwork.id" class="artwork">
      <h2>{{ artwork.title }}</h2>
      <p>{{ artwork.description }}</p>
      <img :src="`/storage/${artwork.image_path}`" alt="Artwork Image" />
      <router-link :to="{ name: 'artwork-details', params: { id: artwork.id } }">View Details</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ArtworksList',
  data() {
    return {
      artworks: [],
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
</style>
