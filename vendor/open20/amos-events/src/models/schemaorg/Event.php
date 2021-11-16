<?php

namespace open20\amos\events\models\schemaorg;

use simialbi\yii2\schemaorg\models\Model;

/**
 * An event happening at a certain time and location, such as a concert,
 * lecture, or festival. Ticketing information may be added via the <a
 * class="localLink" href="http://schema.org/offers">offers</a> property.
 * Repeated events may be structured as separate Event objects.
 *
 */
class Event extends Model
{
    /**
     * The total number of individuals that may attend an event or venue.
     *
     * @var int
     */
    public $maximumAttendeeCapacity;

    /**
     * The typical expected age range, e.g. '7-9', '11-'.
     *
     * @var string
     */
    public $typicalAgeRange;

    /**
     * An organizer of an Event.
     *
     * @var Person|Organization
     */
    public $organizer;

    /**
     * A person attending the event.
     *
     * @var Person|Organization
     */
    public $attendees;

    /**
     * The overall rating, based on a collection of reviews or ratings, of the
     * item.
     *
     * @var AggregateRating
     */
    public $aggregateRating;

    /**
     * An Event that is part of this event. For example, a conference event
     * includes many presentations, each of which is a subEvent of the conference.
     *
     * @var Event
     */
    public $subEvent;

    /**
     * Events that are a part of this event. For example, a conference event
     * includes many presentations, each subEvents of the conference.
     *
     * @var Event
     */
    public $subEvents;

    /**
     * A work featured in some event, e.g. exhibited in an ExhibitionEvent.
     *        Specific subproperties are available for workPerformed (e.g. a
     * play), or a workPresented (a Movie at a ScreeningEvent).
     *
     * @var CreativeWork
     */
    public $workFeatured;

    /**
     * The subject matter of the content.
     *
     * @var Thing
     */
    public $about;

    /**
     * The language of the content or performance or used in an action. Please use
     * one of the language codes from the <a
     * href="http://tools.ietf.org/html/bcp47">IETF BCP 47 standard</a>. See also
     * <a class="localLink"
     * href="http://schema.org/availableLanguage">availableLanguage</a>.
     *
     * @var string|Language
     */
    public $inLanguage;

    /**
     * A person or organization that supports (sponsors) something through some
     * kind of financial contribution.
     *
     * @var Person|Organization
     */
    public $funder;

    /**
     * The number of attendee places for an event that remain unallocated.
     *
     * @var int
     */
    public $remainingAttendeeCapacity;

    /**
     * The maximum physical attendee capacity of an <a class="localLink"
     * href="http://schema.org/Event">Event</a> whose <a class="localLink"
     * href="http://schema.org/eventAttendanceMode">eventAttendanceMode</a> is <a
     * class="localLink"
     * href="http://schema.org/OfflineEventAttendanceMode">OfflineEventAttendanceMode</a>
     * (or the offline aspects, in the case of a <a class="localLink"
     * href="http://schema.org/MixedEventAttendanceMode">MixedEventAttendanceMode</a>).
     *
     * @var int
     */
    public $maximumPhysicalAttendeeCapacity;

    /**
     * An intended audience, i.e. a group for whom something was created.
     *
     * @var Audience
     */
    public $audience;

    /**
     * An actor, e.g. in tv, radio, movie, video games etc., or in an event.
     * Actors can be associated with individual items or with a series, episode,
     * clip.
     *
     * @var Person
     */
    public $actor;

    /**
     * The main performer or performers of the event&#x2014;for example, a
     * presenter, musician, or actor.
     *
     * @var Person|Organization
     */
    public $performers;

    /**
     * The end date and time of the item (in <a
     * href="http://en.wikipedia.org/wiki/ISO_8601">ISO 8601 date format</a>).
     *
     * @var string
     */
    public $endDate;

    /**
     * Associates an <a class="localLink" href="http://schema.org/Event">Event</a>
     * with a <a class="localLink" href="http://schema.org/Schedule">Schedule</a>.
     * There are circumstances where it is preferable to share a schedule for a
     * series of
     *       repeating events rather than data on the individual events
     * themselves. For example, a website or application might prefer to publish a
     * schedule for a weekly
     *       gym class rather than provide data on every event. A schedule could
     * be processed by applications to add forthcoming events to a calendar. An <a
     * class="localLink" href="http://schema.org/Event">Event</a> that
     *       is associated with a <a class="localLink"
     * href="http://schema.org/Schedule">Schedule</a> using this property should
     * not have <a class="localLink"
     * href="http://schema.org/startDate">startDate</a> or <a class="localLink"
     * href="http://schema.org/endDate">endDate</a> properties. These are instead
     * defined within the associated
     *       <a class="localLink" href="http://schema.org/Schedule">Schedule</a>,
     * this avoids any ambiguity for clients using the data. The property might
     * have repeated values to specify different schedules, e.g. for different
     * months
     *       or seasons.
     *
     * @var Schedule
     */
    public $eventSchedule;

    /**
     * The time admission will commence.
     *
     * @var string
     */
    public $doorTime;

    /**
     * A secondary contributor to the CreativeWork or Event.
     *
     * @var Person|Organization
     */
    public $contributor;

    /**
     * A review of the item.
     *
     * @var Review
     */
    public $review;

    /**
     * A person or organization that supports a thing through a pledge, promise,
     * or financial contribution. e.g. a sponsor of a Medical Study or a corporate
     * sponsor of an event.
     *
     * @var Person|Organization
     */
    public $sponsor;

    /**
     * The location of for example where the event is happening, an organization
     * is located, or where an action takes place.
     *
     * @var string|Place|VirtualLocation|PostalAddress
     */
    public $location;

    /**
     * The CreativeWork that captured all or part of this Event.
     *
     * @var CreativeWork
     */
    public $recordedIn;

    /**
     * The person or organization who wrote a composition, or who is the composer
     * of a work performed at some event.
     *
     * @var Organization|Person
     */
    public $composer;

    /**
     * A flag to signal that the item, event, or place is accessible for free.
     *
     * @var bool
     */
    public $isAccessibleForFree;

    /**
     * A performer at the event&#x2014;for example, a presenter, musician, musical
     * group or actor.
     *
     * @var Organization|Person
     */
    public $performer;

    /**
     * A person or organization attending the event.
     *
     * @var Organization|Person
     */
    public $attendee;

    /**
     * A work performed in some event, for example a play performed in a
     * TheaterEvent.
     *
     * @var CreativeWork
     */
    public $workPerformed;

    /**
     * An eventStatus of an event represents its status; particularly useful when
     * an event is cancelled or rescheduled.
     *
     * @var EventStatusType
     */
    public $eventStatus;

    /**
     * A director of e.g. tv, radio, movie, video gaming etc. content, or of an
     * event. Directors can be associated with individual items or with a series,
     * episode, clip.
     *
     * @var Person
     */
    public $director;

    /**
     * An offer to provide this item&#x2014;for example, an offer to sell a
     * product, rent the DVD of a movie, perform a service, or give away tickets
     * to an event. Use <a class="localLink"
     * href="http://schema.org/businessFunction">businessFunction</a> to indicate
     * the kind of transaction offered, i.e. sell, lease, etc. This property can
     * also be used to describe a <a class="localLink"
     * href="http://schema.org/Demand">Demand</a>. While this property is listed
     * as expected on a number of common types, it can be used in others. In that
     * case, using a second type, such as Product or a subtype of Product, can
     * clarify the nature of the offer.
     *
     * @var Demand|Offer
     */
    public $offers;

    /**
     * The duration of the item (movie, audio recording, event, etc.) in <a
     * href="http://en.wikipedia.org/wiki/ISO_8601">ISO 8601 date format</a>.
     *
     * @var Duration
     */
    public $duration;

    /**
     * The maximum physical attendee capacity of an <a class="localLink"
     * href="http://schema.org/Event">Event</a> whose <a class="localLink"
     * href="http://schema.org/eventAttendanceMode">eventAttendanceMode</a> is <a
     * class="localLink"
     * href="http://schema.org/OnlineEventAttendanceMode">OnlineEventAttendanceMode</a>
     * (or the online aspects, in the case of a <a class="localLink"
     * href="http://schema.org/MixedEventAttendanceMode">MixedEventAttendanceMode</a>).
     *
     * @var int
     */
    public $maximumVirtualAttendeeCapacity;

    /**
     * Organization or person who adapts a creative work to different languages,
     * regional differences and technical requirements of a target market, or that
     * translates during some event.
     *
     * @var Person|Organization
     */
    public $translator;

    /**
     * The eventAttendanceMode of an event indicates whether it occurs online,
     * offline, or a mix.
     *
     * @var EventAttendanceModeEnumeration
     */
    public $eventAttendanceMode;

    /**
     * The start date and time of the item (in <a
     * href="http://en.wikipedia.org/wiki/ISO_8601">ISO 8601 date format</a>).
     *
     * @var string
     */
    public $startDate;

    /**
     * An event that this event is a part of. For example, a collection of
     * individual music performances might each have a music festival as their
     * superEvent.
     *
     * @var Event
     */
    public $superEvent;

    /**
     * Used in conjunction with eventStatus for rescheduled or cancelled events.
     * This property contains the previously scheduled start date. For rescheduled
     * events, the startDate property should be used for the newly scheduled start
     * date. In the (rare) case of an event that has been postponed and
     * rescheduled multiple times, this field may be repeated.
     *
     * @var string
     */
    public $previousStartDate;

    /**
     * The identifier property represents any kind of identifier for any kind of
     * <a class="localLink" href="http://schema.org/Thing">Thing</a>, such as
     * ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for
     * representing many of these, either as textual strings or as URL (URI)
     * links. See <a href="/docs/datamodel.html#identifierBg">background notes</a>
     * for more details.
     *
     * @var string|PropertyValue
     */
    public $identifier;

    /**
     * URL of a reference Web page that unambiguously indicates the item's
     * identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or
     * official website.
     *
     * @var string
     */
    public $sameAs;

    /**
     * URL of the item.
     *
     * @var string
     */
    public $url;

    /**
     * An alias for the item.
     *
     * @var string
     */
    public $alternateName;

    /**
     * An image of the item. This can be a <a class="localLink"
     * href="http://schema.org/URL">URL</a> or a fully described <a
     * class="localLink" href="http://schema.org/ImageObject">ImageObject</a>.
     *
     * @var ImageObject|string
     */
    public $image;

    /**
     * The name of the item.
     *
     * @var string
     */
    public $name;

    /**
     * A description of the item.
     *
     * @var string
     */
    public $description;

    /**
     * A sub property of description. A short description of the item used to
     * disambiguate from other, similar items. Information from other properties
     * (in particular, name) may be necessary for the description to be useful for
     * disambiguation.
     *
     * @var string
     */
    public $disambiguatingDescription;

    /**
     * A CreativeWork or Event about this Thing.
     *
     * @var Event|CreativeWork
     */
    public $subjectOf;

    /**
     * An additional type for the item, typically used for adding more specific
     * types from external vocabularies in microdata syntax. This is a
     * relationship between something and a class that the thing is in. In RDFa
     * syntax, it is better to use the native RDFa syntax - the 'typeof' attribute
     * - for multiple types. Schema.org tools may have only weaker understanding
     * of extra types, in particular those defined externally.
     *
     * @var string
     */
    public $additionalType;

    /**
     * Indicates a potential Action, which describes an idealized action in which
     * this thing would play an 'object' role.
     *
     * @var Action
     */
    public $potentialAction;

    /**
     * Indicates a page (or other CreativeWork) for which this thing is the main
     * entity being described. See <a
     * href="/docs/datamodel.html#mainEntityBackground">background notes</a> for
     * details.
     *
     * @var CreativeWork|string
     */
    public $mainEntityOfPage;

}
