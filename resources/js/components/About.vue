<template>
  <div
    v-click-outside="goBack"
    class="fixed inset-0 md:right-0 md:left-auto transition md:w-84 xl:w-5/12 overflow-auto pt-64 md:pt-0"
    style="max-width: 40rem"
  >
    <div class="px-12 md:px-24 pt-16 pb-24 flex-col bg-tan-100 flex">
      <span class="text-xs text-center mb-3 font-mono uppercase font-bold">Mapping May 4</span>
      <h1
        class="uppercase font-display text-3xl leading-none self-center text-center leading-tight select-none font-black self-center"
      >
        About
        the
        <br />Project
      </h1>

      <p class="text-sm leading-normal mt-10">
        This website draws from the 110 oral histories in the
        <a
          href="https://www.library.kent.edu/special-collections-and-archives/kent-state-shootings-may-4-collection"
          class="underline"
        >May 4 Collection, Kent State Special Collections &amp; Archives</a>. It maps stories from those histories that describe memories of events at a particular place in Kent between May 1st and May 5th, 1970. This initial version does not include stories about sites within the National Historic Landmark (the spaces near the shooting). We hope to extend this project to include those stories as well as add oral histories collected by the
        <a
          href="https://www.kentohiohistory.org"
          class="underline"
        >Kent Historical Society</a>.
      </p>

      <h1
        class="uppercase font-display text-lg mt-10 leading-none font-black border-t border-black pt-3"
      >PEOPLE</h1>

      <div class="flex mt-8">
        <p class="flex-grow pr-5">
          <a
            href="https://www.kent.edu/geography/profile/jennifer-mapes"
            target="_blank"
            class="underline"
          >Jen Mapes</a> is an Associate Professor in the Department of Geography at Kent State University.
        </p>

        <img
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/jen.jpg"
          alt="Jennifer Mapes"
          class="self-start rounded-full w-16 h-16 object-cover md:w-24 md:h-24"
        />
      </div>

      <div class="flex mt-8">
        <p class="flex-grow pr-5">
          <a
            href="https://www.kent.edu/spcs/sara-koopman-0"
            target="_blank"
            class="underline"
          >Sara Koopman</a> is an Assistant Professor in the School of Peace and Conflict Studies at Kent State University.
        </p>
        <img
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/sara.jpg"
          alt="Jennifer Mapes"
          class="self-start rounded-full w-16 h-16 object-cover md:w-24 md:h-24"
        />
      </div>

      <div class="flex mt-8">
        <p class="flex-grow pr-5 mr-16">
          <i>Special thanks to the students who worked on this project: Chris Willer, Robin Burkhardt, and Evelyn Wogoman.</i>
        </p>
      </div>
    </div>

    <div class="px-12 md:px-24 py-16 bg-white">
      <h1
        class="uppercase font-display text-lg mt-10 leading-none font-black border-t border-black pt-3"
      >Collaborating organizations</h1>

      <div class="flex flex-col mt-10">
        <a
          href="https://www.kent.edu/may4visitorscenter"
          class="underline"
          target="_blank"
        >May 4th Visitor Center</a>

        <a
          href="https://www.kentohiohistory.org"
          class="underline mt-3"
          target="_blank"
        >Kent State Historical Society</a>

        <a
          href="https://www.library.kent.edu/special-collections-and-archives/kent-state-shootings-may-4-collection"
          class="underline mt-3"
          target="_blank"
        >Kent State Special Collections &amp; Archives</a>

        <a href="https://eachevery.com" class="underline mt-3" target="_blank">Each + Every</a>
      </div>

      <div class="flex justify-center mt-24">
        <img
          class="w-24 object-contain self-center mx-5"
          alt="Be the Change - May 4 Visitors Center"
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/be-the-change.png"
        />
        <img
          class="w-32 object-contain self-center mx-5"
          alt="Kent Historical Society Museum"
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/kent-historical-society.png"
        />
      </div>

      <div class="flex justify-center mt-5">
        <img
          class="w-24 object-contain self-center mx-5"
          alt="Embrace Kent State University Libraries - Digital Collections"
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/ksu-libraries.png"
        />

        <img
          class="w-32 object-contain self-center mx-5"
          alt="Kent State University"
          src="http://nhmisc.s3.amazonaws.com/ksug/assets/kent-state-university.png"
        />
      </div>

      <div class="flex mt-8 justify-center">
        <img
          class="w-24 object-contain self-center mx-5"
          alt="Each + Every"
          src="http://nhmisc.s3.amazonaws.com/ksug/EELogo-Black-Full.png"
        />
        <div class="w-32 mx-5"></div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    goBack() {
      this.$router.push("/");
    }
  }
};
</script>