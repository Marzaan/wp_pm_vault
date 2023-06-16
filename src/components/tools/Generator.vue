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
                v-model="numLength"
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
                  :checked="includeUpperCase"
                  @change="generatePassword"
                  v-model="includeUpperCase"
              />
              <label class="form-check-label" for="checkCapAToZ">A-Z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSmlaToz"
                  :checked="includeLowerCase"
                  @change="generatePassword"
                  v-model="includeLowerCase"
              />
              <label class="form-check-label" for="checkSmlaToz">a-z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkNum"
                  :checked="includeNumbers"
                  @change="generatePassword"
                  v-model="includeNumbers"
              />
              <label class="form-check-label" for="checkNum">0-9</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSpecial"
                  :checked="includeSpecialChars"
                  @change="generatePassword"
                  v-model="includeSpecialChars"
              />
              <label class="form-check-label" for="checkSpecial">!@#$%^&*</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkAvoidAmb"
                  :checked="avoidAmbiguousChars"
                  @change="generatePassword"
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
import Chance from 'chance';

export default {
  name: "Generator",
  data() {
    return {
      numLength: 5,
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
    numLength() {
      this.numLength = this.numLength > 99 ? 99 : this.numLength;
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
      if(this.passwordType === 'password') {
        this.generatePassword();
      } else {
        this.generatePhrase();
      }
    }
  },
  methods: {
    setPasswordType( e ) {
      this.passwordType = e.target.value;
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
      let passwordString = '';
      let passStringLen;
      let generatedPassLen;
      let character;

      for (let i = 0; i < this.minSpecialChar; i++) {
        character = specialChars.charAt(Math.floor(Math.random() * 30));
        generatedPassword += character;
      }

      for (let i = 0; i < this.minNumber; i++) {
        character = Math.floor(Math.random() * 10);
        generatedPassword += character;
      }

      if (this.includeNumbers) {
        passwordString += digitChars;
        if (this.minNumber === 0) {
          character = Math.floor(Math.random() * 10);
          generatedPassword += character;
        }
      }

      if (this.includeSpecialChars) {
        passwordString += specialChars;
        if (this.minSpecialChar === 0) {
          character = specialChars.charAt(Math.floor(Math.random() * 30));
          generatedPassword += character;
        }
      }

      if (this.includeUpperCase) {
        passwordString += uppercaseChars;
        character = uppercaseChars.charAt(Math.floor(Math.random() * 26));
        generatedPassword += character;
      }

      if (this.includeLowerCase) {
        passwordString += lowercaseChars;
        character = lowercaseChars.charAt(Math.floor(Math.random() * 26));
        generatedPassword += character;
      }

      passStringLen = passwordString.length;
      generatedPassLen = generatedPassword.length;

      if (passStringLen === 0) {
        this.includeLowerCase = true;
      }

      while (generatedPassLen < this.numLength) {
        character = passwordString.charAt(Math.floor(Math.random() * passStringLen));
        generatedPassword += character;
        generatedPassLen++;
      }

      if (this.avoidAmbiguousChars) {
        generatedPassword = generatedPassword.replace(/(0O|1l|2Z|5S|8B|O0|l1|Z2|S5|B8)/g, '2k');
      }

      this.numLength = generatedPassLen;
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
      navigator.clipboard.writeText(this.showPassword);
    }
  },
  mounted() {
    this.generatePassword();
  }
};
</script>
