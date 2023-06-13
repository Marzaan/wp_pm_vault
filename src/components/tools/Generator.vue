<template>
  <div class="container">
    <h3>Generator</h3>
    <hr class="bg-danger border-1 border-top border-dark"/>
    <div class="card text-dark bg-light mb-3 w-100">
      <div class="card-body text-center">
        <p class="card-text">{{ password }}</p>
      </div>
    </div>
    <form>
      <div class="row">
        <div class="col-12 mb-4">
          <p class="fw-bold">Password type</p>
          <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                id="passwordRadioId"
                value="password"
                @change=""
            />
            <label class="form-check-label" htmlFor="passwordRadioId">Password</label>
          </div>
          <div class="form-check form-check-inline">
            <input
                class="form-check-input"
                type="radio"
                id="phraseRadioId"
                value="phrase"
                @change=""
            />
            <label class="form-check-label" htmlFor="phraseRadioId">Passphrase</label>
          </div>
        </div>
        <template v-if="passwordType === 'password'">
          <div class="col-sm-12 col-md-4 mb-4">
            <label htmlFor="inputLength" class="form-label fw-bold">Length</label>
            <input
                id="inputLength"
                class="form-control"
                type="number"
                value={numLength}
                onBlur={generatePassword}
                @change=""
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label htmlFor="inputNumber" class="form-label fw-bold">Minimum numbers</label>
            <input
                id="inputNumber"
                class="form-control"
                type="number"
                value={minNumber}
                @change=""
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label htmlFor="inputSpecial" class="form-label fw-bold">Minimum special</label>
            <input
                id="inputSpecial"
                class="form-control"
                type="number"
                value={minSpecialChar}
                @change=""
            />
          </div>
          <div class="col-12 mb-4">
            <p class="fw-bold">Options</p>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkCapAToZ"
                  checked={includeUpperCase}
                  @change=""
              />
              <label class="form-check-label" htmlFor="checkCapAToZ">A-Z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSmlaToz"
                  checked={includeLowerCase}
                  @change=""
              />
              <label class="form-check-label" htmlFor="checkSmlaToz">a-z</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkNum"
                  checked={includeNumbers}
                  @change=""
              />
              <label class="form-check-label" htmlFor="checkNum">0-9</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkSpecial"
                  checked={includeSpecialChars}
                  @change=""
              />
              <label class="form-check-label" htmlFor="checkSpecial">!@#$%^&*</label>
            </div>
            <div class="form-check">
              <input
                  class="form-check-input"
                  type="checkbox"
                  id="checkAvoidAmb"
                  checked={avoidAmbiguousChars}
                  @change=""
              />
              <label class="form-check-label" htmlFor="checkAvoidAmb">Avoid ambiguous characters</label>
            </div>
          </div>
        </template>
        <template v-else-if="passwordType === 'phrase'">
          <div class="col-sm-12 col-md-4 mb-4">
            <label htmlFor="inputWordLength" class="form-label fw-bold">Number of Word</label>
            <input
                id="inputWordLength"
                class="form-control"
                type="number"
                value={wordLength}
                onBlur={generatePhrase}
                @change=""
            />
          </div>
          <div class="col-sm-12 col-md-4 mb-4">
            <label htmlFor="inputWordSepa" class="form-label fw-bold">Word Separator</label>
            <input
                id="inputWordSepa"
                class="form-control"
                type="text"
                maxLength={1}
                value={wordSeparator}
                @change=""
            />
          </div>
        </template>
        <div class="col-12 mb-4">
          <button type="button" class="btn btn-dark m-1 hover-btn" @click="">Regenerate Password</button>
          <button type="button" class="btn btn-outline-secondary m-1" @click="">Copy Password</button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: "Generator",
  data() {
    return {
      passwordType: "password",
      password: "dummypassword",
    };
  },
};
</script>
