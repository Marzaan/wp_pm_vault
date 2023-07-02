<template>
  <div class="container">
    <h3>Generator</h3>
    <hr class="bg-danger border-1 border-top border-dark"/>
    <div class="card text-dark bg-light mb-3 w-100">
      <div class="card-body text-center">
        <p class="card-text">{{ showPassword }}</p>
      </div>
    </div>
    <form>
      <div class="row">
        <div class="col-12 mb-4">
          <h6 class="fw-bold">Password Type</h6>
          <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                id="passwordRadioId"
                value="password"
                :checked="passwordType === 'password'"
                @change="setPasswordType"
            />
            <label class="form-check-label" for="passwordRadioId">Password</label>
          </div>
          <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                id="phraseRadioId"
                value="phrase"
                :checked="passwordType === 'phrase'"
                @change="setPasswordType"
            />
            <label class="form-check-label" for="phraseRadioId">Passphrase</label>
          </div>
        </div>
        <template v-if="passwordType === 'password'">
          <div class="col-sm-12 col-md-4 mb-4">
            <label for="inputLength" class="form-label fw-bold">Length</label>
            <input
                id="inputLength"
                class="form-control"
                type="number"
                @blur="generatePassword"
                v-model="charLength"
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label for="inputNumber" class="form-label fw-bold">Minimum numbers</label>
            <input
                id="inputNumber"
                class="form-control"
                type="number"
                @change="generatePassword"
                v-model="minNumber"
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label for="inputSpecial" class="form-label fw-bold">Minimum special</label>
            <input
                id="inputSpecial"
                class="form-control"
                type="number"
                @change="generatePassword"
                v-model="minSpecialChar"
            />
          </div>
          <div class="col-12 mb-4">
            <h6 class="fw-bold">Options</h6>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkCapAToZ"
                  v-model="includeUpperCase"
              />
              <label class="form-check-label" for="checkCapAToZ">A-Z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSmlaToz"
                  v-model="includeLowerCase"
              />
              <label class="form-check-label" for="checkSmlaToz">a-z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkNum"
                  v-model="includeNumbers"
              />
              <label class="form-check-label" for="checkNum">0-9</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSpecial"
                  v-model="includeSpecialChars"
              />
              <label class="form-check-label" for="checkSpecial">!@#$%^&*</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkAvoidAmb"
                  v-model="avoidAmbiguousChars"
              />
              <label class="form-check-label" for="checkAvoidAmb">Avoid ambiguous characters</label>
            </div>
          </div>
        </template>
        <template v-else-if="passwordType === 'phrase'">
          <div class="col-sm-12 col-md-4 mb-4">
            <label for="inputWordLength" class="form-label fw-bold">Number of Word</label>
            <input
                id="inputWordLength"
                class="form-control"
                type="number"
                @blur="generatePhrase"
                v-model="wordLength"
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label for="inputWordSepa" class="form-label fw-bold">Word Separator</label>
            <input
                id="inputWordSepa"
                class="form-control"
                type="text"
                maxLength='1'
                @change="generatePhrase"
                v-model="wordSeparator"
            />
          </div>
        </template>
        <div class="col-12 mb-4">
          <button type="button" class="btn btn-dark m-1 hover-btn" @click="regeneratePassword">Regenerate Password</button>
          <button type="button" class="btn btn-outline-secondary m-1" @click="copyToClipboard">Copy Password</button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Vue from 'vue';
import Chance from 'chance';
import Toasted from 'vue-toasted';

export default {
  name: "Generator",
  data() {
    return {
      charLength: 5,
      minNumber: 1,
      minSpecialChar: 1,
      includeUpperCase: true,
      includeLowerCase: true,
      includeNumbers: true,
      includeSpecialChars: true,
      avoidAmbiguousChars: false,
      wordLength: 5,
      wordSeparator: "-",
      showPassword: '',
      passwordType: "password",
    };
  },
  watch: {
    charLength() {
      this.charLength = this.charLength > 99 ? 99 : this.charLength;
    },
    minNumber() {
      this.minNumber = this.minNumber > 9 ? 9 : this.minNumber;
    },
    minSpecialChar() {
      this.minSpecialChar = this.minSpecialChar > 9 ? 9 : this.minSpecialChar;
    },
    wordLength() {
      this.wordLength = this.wordLength > 20 ? 20 : this.wordLength;
    },
    passwordType() {
      this.regeneratePassword();
    },
    includeLowerCase() {
      this.generatePassword();
    },
    includeUpperCase() {
      this.generatePassword();
    },
    includeNumbers() {
      this.generatePassword();
    },
    includeSpecialChars() {
      this.generatePassword();
    },
    avoidAmbiguousChars() {
      this.generatePassword();
    }
  },
  methods: {
    // Toaster
    showToast(message, type) {
      this.$toasted.show(message, {
        theme: "toasted-primary",
        position: "top-center",
        duration: 3000,
        type: type,
      });
    },

    setPasswordType(event) {
      this.passwordType = event.target.value;
    },

    generatePhrase () {
        const chance = new Chance();
        const generatedPhrase  = chance.sentence({ words: this.wordLength }).replace(/\s/g, this.wordSeparator);
        this.showPassword = generatedPhrase;
    },

    generatePassword() {
      const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
      const digitChars = '0123456789';
      const specialChars = '!@#$%^&*()_+~`}{[]:;?><,./-=';

      let generatedPassword = '';
      let generatedPasswordLength;
      let selectedCharSets = '';
      let selectedCharSetsLength;
      let character;

      // Fill the minimum special character
      for (let i = 0; i < this.minSpecialChar; i++) {
        character = specialChars.charAt(Math.floor(Math.random() * 30));
        generatedPassword += character;
      }

      // Fill the minimum number
      for (let i = 0; i < this.minNumber; i++) {
        character = Math.floor(Math.random() * 10);
        generatedPassword += character;
      }

      // Include Number if selected but not filled the minimum number
      if (this.includeNumbers) {
        selectedCharSets += digitChars;
        if (!this.minNumber) {
          character = Math.floor(Math.random() * 10);
          generatedPassword += character;
        }
      }

      // Include Special Chars if selected but not filled the minimum special chars
      if (this.includeSpecialChars) {
        selectedCharSets += specialChars;
        if (!this.minSpecialChar) {
          character = specialChars.charAt(Math.floor(Math.random() * 30));
          generatedPassword += character;
        }
      }

      // Include Lowercase Chars if selected
      if (this.includeUpperCase) {
        selectedCharSets += uppercaseChars;
        character = uppercaseChars.charAt(Math.floor(Math.random() * 26));
        generatedPassword += character;
      }

      // Include Uppercase Chars if selected
      if (this.includeLowerCase) {
        selectedCharSets += lowercaseChars;
        character = lowercaseChars.charAt(Math.floor(Math.random() * 26));
        generatedPassword += character;
      }

      // Get the length of selected charsets
      selectedCharSetsLength = selectedCharSets.length;
      generatedPasswordLength = generatedPassword.length;

      // Update the character length if generated password length gets higher
      this.charLength = (this.charLength < generatedPasswordLength) ? generatedPasswordLength : this.charLength;

      // If there is no checkbox checked, include lowercase chars by default
      if (selectedCharSetsLength === 0) {
        this.includeLowerCase = true;
      }

      // Fill the remaining password length
      while (generatedPasswordLength < this.charLength) {
        character = selectedCharSets.charAt(Math.floor(Math.random() * selectedCharSetsLength));
        generatedPassword += character;
        generatedPasswordLength++;
      }

      // Remove ambiguous chars
      if (this.avoidAmbiguousChars) {
        generatedPassword = generatedPassword.replace(/(0O|1l|2Z|5S|8B|O0|l1|Z2|S5|B8)/g, '2k');
      }

      // Assign the generated password to the view field
      this.showPassword = generatedPassword;
    },

    regeneratePassword(){
      if(this.passwordType === 'password') {
        this.generatePassword();
      } else {
        this.generatePhrase();
      }
    },

    copyToClipboard(){
      this.showToast('Password copied', 'success');
      navigator.clipboard.writeText(this.showPassword);
    }
  },
  mounted() {
    this.generatePassword();
  },
  created() {
    Vue.use(Toasted);
  },
};
</script>
