<template>
  <div>
    <h1>Artist Dashboard</h1>
    <p>Welcome, Artist!</p>
    
    <div>
      <h2>Your Artworks</h2>
      <ul>
        <li v-for="artwork in artworks" :key="artwork.id">
          <h3>{{ artwork.title }}</h3>
          <p>{{ artwork.description }}</p>
          <img :src="`/storage/${artwork.image_path}`" alt="Artwork Image" />
          <button @click="prepareEditArtwork(artwork)">Edit</button>
          <button @click="deleteArtwork(artwork.id)">Delete</button>
        </li>
      </ul>
    </div>

    <div>
      <h2>Add New Artwork</h2>
      <form @submit.prevent="addArtwork">
        <div>
          <label for="title">Title</label>
          <input v-model="newArtwork.title" type="text" id="title" required />
        </div>
        <div>
          <label for="description">Description</label>
          <input v-model="newArtwork.description" type="text" id="description" required />
        </div>
        <div>
          <label for="image">Image</label>
          <input @change="handleFileUpload" type="file" id="image" required />
        </div>
        <button type="submit">Add Artwork</button>
      </form>
    </div>

    <div v-if="editArtworkForm.visible">
      <h2>Edit Artwork</h2>
      <form @submit.prevent="updateArtwork">
        <div>
          <label for="edit-title">Title</label>
          <input v-model="editArtworkForm.data.title" type="text" id="edit-title" required />
        </div>
        <div>
          <label for="edit-description">Description</label>
          <input v-model="editArtworkForm.data.description" type="text" id="edit-description" required />
        </div>
        <div>
          <label for="edit-image">Image</label>
          <input @change="handleEditFileUpload" type="file" id="edit-image" />
        </div>
        <button type="submit">Update Artwork</button>
        <button @click="cancelEdit">Cancel</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';

export default {
  name: 'ArtistDashboard',
  setup() {
    const artworks = ref([]);
    const newArtwork = ref({
      title: '',
      description: '',
      image: null
    });
    const editArtworkForm = ref({
      visible: false,
      data: {
        id: null,
        title: '',
        description: '',
        image: null
      }
    });

    const fetchArtworks = async () => {
      try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/artworks', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        artworks.value = response.data;
      } catch (error) {
        console.error('Error fetching artworks:', error);
      }
    };

    const handleFileUpload = (event) => {
      newArtwork.value.image = event.target.files[0];
    };

    const handleEditFileUpload = (event) => {
      editArtworkForm.value.data.image = event.target.files[0];
    };

    const addArtwork = async () => {
      try {
        const token = localStorage.getItem('token');
        const formData = new FormData();
        formData.append('title', newArtwork.value.title);
        formData.append('description', newArtwork.value.description);
        formData.append('image', newArtwork.value.image);

        const response = await axios.post('/api/artworks/store', formData, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        artworks.value.push(response.data);
        newArtwork.value = { title: '', description: '', image: null };
      } catch (error) {
        console.error('Error adding artwork:', error);
      }
    };

    const prepareEditArtwork = (artwork) => {
      editArtworkForm.value.data.id = artwork.id;
      editArtworkForm.value.data.title = artwork.title;
      editArtworkForm.value.data.description = artwork.description;
      editArtworkForm.value.data.image = null;
      editArtworkForm.value.visible = true;
    };

    const updateArtwork = async () => {
      try {
        const token = localStorage.getItem('token');
        const formData = new FormData();
        formData.append('title', editArtworkForm.value.data.title);
        formData.append('description', editArtworkForm.value.data.description);
        if (editArtworkForm.value.data.image) {
          formData.append('image', editArtworkForm.value.data.image);
        }

        const response = await axios.put(`/api/artworks/update/${editArtworkForm.value.data.id}`, formData, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'multipart/form-data'
          }
        });

        const updatedArtwork = response.data;
        const index = artworks.value.findIndex(art => art.id === updatedArtwork.id);
        artworks.value[index] = updatedArtwork;

        cancelEdit();
      } catch (error) {
        console.error('Error updating artwork:', error);
      }
    };

    const cancelEdit = () => {
      editArtworkForm.value.visible = false;
      editArtworkForm.value.data = { id: null, title: '', description: '', image: null };
    };

    const deleteArtwork = async (id) => {
      try {
        const token = localStorage.getItem('token');
        await axios.delete(`/api/artworks/delete/${id}`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        artworks.value = artworks.value.filter(artwork => artwork.id !== id);
      } catch (error) {
        console.error('Error deleting artwork:', error);
      }
    };

    fetchArtworks();

    return {
      artworks,
      newArtwork,
      editArtworkForm,
      handleFileUpload,
      handleEditFileUpload,
      addArtwork,
      prepareEditArtwork,
      updateArtwork,
      cancelEdit,
      deleteArtwork
    };
  }
};
</script>

<style scoped>
.auth-form {
  max-width: 400px;
  margin: 0 auto;
}
.error {
  color: red;
}
</style>
