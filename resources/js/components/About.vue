<template>
    <div
        v-click-outside="goBack"
        class="fixed inset-0 md:right-0 md:left-auto transition md:w-84 xl:w-5/12 overflow-auto pt-64 md:pt-0 md:max-w-base"
        id="about-container"
    >
        <div
            class="absolute top-0 left-0 right-0  h-64 md:hidden"
            @click="goBack"
        ></div>

        <page-section class="bg-white" id="about-may-4">
            <div class="-mr-12">
                <img
                    src="//nhmisc.s3.amazonaws.com/ksug/d4dff96a075d8cd5f98a56aad48e0988.jpg"
                    class="w-full mb-2"
                />
                <span class="text-2xs mt-2 font-mono leading-normal"
                    >Kent State University Libraries, Special Collections</span
                >
            </div>

            <header-40>ABOUT MAY 4</header-40>

            <paragraph
                >At Kent State University in Kent, Ohio, on May 4, 1970, 28
                National Guard troops turned in unison and fired 67 shots at
                student peace protesters, killing 4 and wounding 9, leaving one
                paralyzed. On May 1st students began a series of protests in
                response to Nixon’s expansion of the war from Vietnam to
                Cambodia. By May 4th they were also protesting the occupation of
                campus by nearly a thousand troops, more than 100 trucks, 12
                armored vehicles, 3 tanks with mortar launchers, and 13
                helicopters. People across the city and campus were traumatized
                in various ways. After the shootings some community members said
                that the students deserved to be shot. Even 50 years later
                division and resentments continue.</paragraph
            >
        </page-section>

        <page-section class="bg-blue" id="about-the-project">
            <header-40>
                About this
                <br />Project
            </header-40>

            <paragraph>
                This web app draws from the oral histories in the
                <a
                    href="https://www.library.kent.edu/special-collections-and-archives/kent-state-shootings-may-4-collection"
                    class="underline"
                    >May 4 Collection, Kent State Special Collections &amp;
                    Archives</a
                >. It maps stories from those histories that describe memories
                of events at a particular place in Kent between May 1st and May
                5th, 1970 (but does not yet include stories within the National
                Historic Landmark). This web app is designed to serve as a
                digital memorial, to remember and honor these events. We have
                made it both for those who have stories to share, and those who
                are interested in hearing these stories. This web app does not
                aim to establish historical facts. Readers will notice that
                stories associated with some sites are quite contradictory.
                Instead this web app aims to open a space for dialogue in the
                hope of building understanding, connection across difference,
                and ultimately reconciliation. For those seeking a historical
                account we recommend starting with the short booklet
                <a
                    href="http://www.kentstateuniversitypress.com/2012/this-we-know/"
                    target="_blank"
                    class="underline"
                    >This We Know</a
                >.
            </paragraph>
            <header-25>Next Steps</header-25>

            <paragraph>
                We are in the process of extending this project to include the
                stories of what happened right around the site of the shooting
                (the area which is now a National Historic Landmark) as well as
                add more oral histories collected by the
                <a href="https://kenthistoricalsociety.org" class="underline"
                    >Kent Historical Society</a
                >. We are also working to make this web app location aware, add
                virtual walking tours, and highlight the most compelling
                stories. We will be installing wall size static versions of this
                map at the May 4th visitors center and the Kent Historical
                Society in the spring of 2020. We will also be hosting live
                walking dialogues based on this map during the commemoration
                weekend. If you would like to be notified about these, or when
                this web app is updated, please sign up for our low traffic
                email list
                <a
                    href="https://mailchi.mp/531c4843f76c/mappingmay4"
                    class="underline"
                    >here</a
                >.
            </paragraph>
        </page-section>

        <page-section class="bg-tan-300" id="how-to-use">
            <header-40>How to Use this Site</header-40>
            <paragraph
                >We suggest that you start by clicking on a site that means
                something to you. If you have trouble identifying it on the map,
                you can search for it by name in the search bar at the top left.
                It may help you to find your site to toggle between the historic
                aerial photo and the current day street map. You can do this
                with the button in the lower left (when you close this splash
                page). You can also search by name, if you would like to see
                stories by a particular person, and date (between May 1st and
                May 4th). You can also search by all of these categories—for now
                that functions as OR vs AND. Of course you can also search a
                site at random. You can read the stories but we recommend that
                you also listen to them—the audio can be powerful.</paragraph
            >
            <header-25>Join the conversation</header-25>
            <paragraph>
                This web app is a space for dialogue, and we encourage you to
                add your voice to any of the sites. You can share your own
                memories of what happened at a location, but also stories you
                heard from others, or your own impressions of the site or the
                stories associated with it. For now you can only add to sites
                that are currently pinned on the map, but we will soon update it
                to make it possible to comment on any site in Kent, as well as
                to add audio and video comments, as well as historic photos. As
                we work to roll out the next phases of the web app we would also
                appreciate your feedback on this web app and what could be
                improved. Please feel free to
                <a href="#about-us" class="underline">contact us</a>.
            </paragraph>
        </page-section>

        <page-section class="bg-white" id="about-us">
            <header-40>ABOUT US</header-40>

            <div class="flex mt-8">
                <p class="flex-grow pr-5">
                    <a
                        href="https://www.kent.edu/geography/profile/jennifer-mapes"
                        target="_blank"
                        class="underline"
                        >Jen Mapes</a
                    >
                    is an Associate Professor in the Department of Geography at
                    Kent State University.
                    <br />
                    <a
                        href="mailto:jmapes@kent.edu"
                        class="italic no-underline mt-2 text-xs block"
                        >Contact Jen &rarr;</a
                    >
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
                        >Sara Koopman</a
                    >
                    is an Assistant Professor in the School of Peace and
                    Conflict Studies at Kent State University.
                    <br />
                    <a
                        href="mailto:sara.koopman@gmail.com"
                        class="italic no-underline mt-2 text-xs block"
                        >Contact Sara &rarr;</a
                    >
                </p>
                <img
                    src="http://nhmisc.s3.amazonaws.com/ksug/assets/sara.jpg"
                    alt="Jennifer Mapes"
                    class="self-start rounded-full w-16 h-16 object-cover md:w-24 md:h-24"
                />
            </div>

            <div class="flex mt-8">
                <p class="flex-grow pr-5 mr-16">
                    <i
                        >Special thanks to the students who worked on this
                        project: Chris Willer, Robin Burkhardt, and Evelyn
                        Wogoman.</i
                    >
                </p>
            </div>
        </page-section>

        <page-section class="bg-white">
            <header-25>COLLABORATING ORGANIZATIONS</header-25>

            <div class="flex flex-col">
                <a
                    href="https://www.kent.edu/may4visitorscenter"
                    class="underline"
                    target="_blank"
                    >May 4th Visitor Center</a
                >

                <a
                    href="https://www.kentohiohistory.org"
                    class="underline mt-3"
                    target="_blank"
                    >Kent Historical Society and Museum</a
                >

                <a
                    href="https://www.library.kent.edu/special-collections-and-archives/kent-state-shootings-may-4-collection"
                    class="underline mt-3"
                    target="_blank"
                    >Kent State Special Collections &amp; Archives</a
                >

                <a
                    href="https://eachevery.com"
                    class="underline mt-3"
                    target="_blank"
                    >Each + Every</a
                >

                <header-25>FUNDING FROM</header-25>

                <span class="mt-3">Ohio Humanities</span>
                <span class="mt-3">Kent State 50th commemoration funds</span>
                <span class="mt-3"
                    >College of Arts & Sciences of Kent State</span
                >
                <span class="mt-3">University Research Council</span>
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

            <div class="flex mt-8 justify-center mb-16">
                <img
                    class="w-24 object-contain self-center mx-5"
                    alt="Each + Every"
                    src="http://nhmisc.s3.amazonaws.com/ksug/EELogo-Black-Full.png"
                />
                <img
                    class="w-24 object-contain self-center mx-5"
                    alt="Ohio Humanities"
                    src="http://eachevery.s3.amazonaws.com/ksug/ohio-humanities.png"
                />
            </div>
        </page-section>
    </div>
</template>
<script>
import pageSection from "./PageSection";
import header40 from "./Header40";
import header25 from "./Header25";
import paragraph from "./Paragraph";
import $ from "jquery";

export default {
    components: {
        pageSection,
        header40,
        header25,
        paragraph
    },
    methods: {
        goBack() {
            this.$router.push("/");
        }
    },
    mounted() {
        if (window.location.hash.length > 1) {
            $("#about-container").animate(
                {
                    scrollTop: $(window.location.hash).offset().top
                },
                300
            );
        }
    }
};
</script>
