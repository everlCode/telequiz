<template>
  <form @submit.prevent="setWebHook" class="flex flex-col gap-y-5">
    <va-input v-model="url" type="text" placeholder="Server uri" />
    <va-input v-model="token" type="text" placeholder="telegram bot" />
    <va-button type="submit" class="self-end mt-5">
        Set Webhook
    </va-button>
  </form>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      url: "",
      telegram: "everlo_bot",
      token: "5077951331:AAEN44miGjYYmKlp5RUv25yulD_7kHgSarM"
    };
  },
  mounted() {
    this.url = window.location.origin;
    // axios.get('/api/settings/localhost').then((response) => {
    //     console.log(response);
    // })
  },
  methods: {
    setWebHook() {
        let url = 'https://api.telegram.org/bot' + this.token + '/setWebhook?url=' + this.url + '/api/telegram';
        console.log(url);
        axios.get(url).then((response) => {
            alert(response.data.description)
        })
    }
  }
};
</script>
<style scoped></style>
