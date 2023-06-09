<template>
    <div class=" m-auto item-page">
      <div>
        <div class="section-title pb-4">
            <h2>All Items</h2>

            <button @click="" class="btn btn-dark hover-btn">
                New Item
            </button>
        </div>
        <div class="item-listing">
            <div class="listing-header">
                <div class="checkbox-container col-md-2">
                  <input type="checkbox" id="select-all" @click=""/>All
                </div>
                <div class="col-md-6">Name</div>
                <div class="col-md-3">Owner</div>
                  <div class="col-md-1">
                    <div class="three-dot-dropdown">
                      <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="dot"></div><div class="dot"></div><div class="dot"></div>
                      </button>
                      <ul class="dropdown-menu">
                          <div>
                            <li class="dropdown-item" @click="">
                                Permanentlt Delete Selected
                            </li>
                          </div>
                          <div>
                            <li class="dropdown-item" @click="">
                                Move Selected
                            </li>
                            <li class="dropdown-item" @click="">
                                Move Selected To Org..
                            </li>
                            <li class="dropdown-item" @click="">
                                Delete Selected
                            </li>
                          </div>
                      </ul>
                    </div>
                  </div>
            </div>
                    <div class="listing-item">
                        <div class="checkbox-container col-md-2">
                          <input type="checkbox" id="item-1"
                              @change=""
                          />
                          <label htmlFor="item-1"></label>
                        </div>
                        <div class="col-md-6 d-flex">
                          <div class="item-name">
                            <p class="my-0 list-item-name" @click="">Dummy Item</p>
                              <span class="item-type text-muted small">
                                Login Item
                            </span>
                          </div>
                        </div>
                          <div class="col-md-3">
                              <p class="list-own-name" title="Me">
                                Me
                              </p>
                          </div>
                        <div class="col-md-1">
                          <div class="three-dot-dropdown">
                            <button type="button" class="three-dot-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                              <div class="dot"></div><div class="dot"></div><div class="dot"></div>
                            </button>
                            <ul class="dropdown-menu">
                                <div>
                                  <li class="dropdown-item" @click="">
                                      Restore Item
                                  </li>
                                  <li class="dropdown-item" @click="">
                                      Permanentlt Delete
                                  </li>
                                </div>
                                <div>
                                  <li class="dropdown-item" @click="">
                                      Delete Selected
                                  </li>
                                </div>
                            </ul>
                          </div>
                        </div>
                    </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
    name: 'ItemList',
}
</script>

<style>
@import '../../assets/styles/item.scss'
</style>