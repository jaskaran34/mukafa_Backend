<?php

namespace App\Http\Controllers\Api;

/**
 * OpenAPI annotations for the API.
 */

// ==========================
// SECURITY SCHEMES
// ==========================

/**
 * @OA\SecurityScheme(
 *     securityScheme="admin_auth_token",
 *     type="http",
 *     scheme="bearer",
 *     name="Authorization",
 *     description="Admin authentication token, formatted as `1|laravel_sanctum_<token_string>`."
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="partner_auth_token",
 *     type="http",
 *     scheme="bearer",
 *     name="Authorization",
 *     description="Partner authentication token, formatted as `1|laravel_sanctum_<token_string>`."
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="member_auth_token",
 *     type="http",
 *     scheme="bearer",
 *     name="Authorization",
 *     description="Member authentication token, formatted as `1|laravel_sanctum_<token_string>`."
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     description="Standard error response structure",
 *     required={"message"},
 *     @OA\Property(property="message", type="string", description="Error message", example="The provided credentials are incorrect."),
 *     @OA\Property(property="code", type="integer", description="HTTP error code", example=400)
 * )
 * 
 * @OA\Schema(
 *     schema="UnauthenticatedResponse",
 *     description="Response structure when user is unauthenticated",
 *     required={"message"},
 *     @OA\Property(property="message", type="string", description="Error message", example="Unauthenticated.")
 * )
 * 
 * @OA\Schema(
 *     schema="NotFoundResponse",
 *     description="Response structure when resource is not found",
 *     required={"message"},
 *     @OA\Property(property="message", type="string", description="Error message", example="Not found.")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     description="Response structure for validation errors",
 *     @OA\Property(property="message", type="string", description="Error message", example="Validation error occurred."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Detailed validation errors",
 *         @OA\Property(
 *             property="email",
 *             type="array",
 *             @OA\Items(type="string"),
 *             description="List of email validation errors",
 *             example={"The email must be a valid email address."}
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="AdminLoginSuccess",
 *     description="Response structure when admin login is successful",
 *     required={"token"},
 *     @OA\Property(property="token", type="string", description="Generated token for the admin", example="1|laravel_sanctum_j3ffUNZdoP0JZJn0y3GzgcAONlDzekQrUWI2sqk3c473f47b")
 * )
 * 
 * @OA\Schema(
 *     schema="Admin",
 *     required={"id", "role", "name", "email", "number_of_times_logged_in", "last_login_at", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", example=81559391744000),
 *     @OA\Property(property="role", type="integer", example=1, description="Role of the admin (1 = Admin, 2 = Manager)"),
 *     @OA\Property(property="name", type="string", maxLength=64, example="Admin Name"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *     @OA\Property(property="locale", type="string", minLength=5, maxLength=12, nullable=true, example="en_US", description="Locale of the admin"),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, nullable=true, example="USD", description="Preferred currency of the admin"),
 *     @OA\Property(property="time_zone", type="string", nullable=true, example="America/New_York", description="Time zone of the admin"),
 *     @OA\Property(property="number_of_times_logged_in", type="integer", example=6, description="Number of times the admin has logged in"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last login timestamp of the admin"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Creation timestamp of the admin record"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last update timestamp of the admin record"),
 *     @OA\Property(property="avatar", type="string", nullable=true, description="Avatar URL of the admin")
 * )
 * 
 * @OA\Schema(
 *     schema="AdminPartner",
 *     required={},
 *     @OA\Property(property="id", type="integer", example=81559391260672),
 *     @OA\Property(property="network_id", type="integer", example=81559402639360, description="Network ID associated with the partner"),
 *     @OA\Property(property="name", type="string", maxLength=64, example="Partner Name"),
 *     @OA\Property(property="email", type="string", format="email", example="partner@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Timestamp when the email was verified"),
 *     @OA\Property(property="locale", type="string", minLength=5, maxLength=12, nullable=true, example="en_US", description="Locale of the partner"),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, nullable=true, example="USD", description="Preferred currency of the partner"),
 *     @OA\Property(property="time_zone", type="string", nullable=true, example="America/New_York", description="Time zone of the partner"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Indicates if the partner is active"),
 *     @OA\Property(property="number_of_times_logged_in", type="integer", example=6, description="Number of times the partner has logged in"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last login timestamp of the partner"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Creation timestamp of the partner record"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last update timestamp of the partner record"),
 *     @OA\Property(property="avatar", type="string", nullable=true, description="Avatar URL of the partner")
 * )
 * 
 * @OA\Schema(
 *     schema="PartnerLoginSuccess",
 *     required={"token"},
 *     @OA\Property(property="token", type="string", description="Generated authentication token for the partner", example="1|laravel_sanctum_j3ffUNZdoP0JZJn0y3GzgcAONlDzekQrUWI2sqk3c473f47b")
 * )
 * 
 * @OA\Schema(
 *     schema="Partner",
 *     required={},
 *     @OA\Property(property="id", type="integer", example=81559391260672),
 *     @OA\Property(property="network_id", type="integer", example=81559402639360, description="Network ID associated with the partner"),
 *     @OA\Property(property="name", type="string", maxLength=64, example="Partner Name"),
 *     @OA\Property(property="email", type="string", format="email", example="partner@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Timestamp when the email was verified"),
 *     @OA\Property(property="locale", type="string", minLength=5, maxLength=12, nullable=true, example="en_US", description="Locale of the partner"),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, nullable=true, example="USD", description="Preferred currency of the partner"),
 *     @OA\Property(property="time_zone", type="string", nullable=true, example="America/New_York", description="Time zone of the partner"),
 *     @OA\Property(property="number_of_times_logged_in", type="integer", example=6, description="Number of times the partner has logged in"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last login timestamp of the partner"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Creation timestamp of the partner record"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last update timestamp of the partner record"),
 *     @OA\Property(property="avatar", type="string", nullable=true, description="Avatar URL of the partner")
 * )
 * 
 * @OA\Schema(
 *     schema="MemberLoginSuccess",
 *     required={"token"},
 *     @OA\Property(property="token", type="string", description="Generated authentication token for the member", example="1|laravel_sanctum_j3ffUNZdoP0JZJn0y3GzgcAONlDzekQrUWI2sqk3c473f47b")
 * )
 * 
 * @OA\Schema(
 *     schema="MemberRegistration",
 *     required={},
 *     @OA\Property(property="email", type="string", format="email", example="email@example.com"),
 *     @OA\Property(property="name", type="string", maxLength=64, example="John Doe"),
 *     @OA\Property(property="password", type="string", maxLength=48, nullable=true, example="mypassword", description="Password for the member"),
 *     @OA\Property(property="time_zone", type="string", nullable=true, example="America/New_York", description="Time zone of the member"),
 *     @OA\Property(property="locale", type="string", minLength=5, maxLength=12, nullable=true, example="en_US", description="Locale of the member"),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, nullable=true, example="USD", description="Preferred currency of the member"),
 *     @OA\Property(property="accepts_emails", type="integer", enum={0, 1}, example=1, nullable=true, description="Indicates if the member accepts emails"),
 *     @OA\Property(property="send_mail", type="integer", enum={0, 1}, example=0, nullable=true, description="If set to 1, sends an email with the password to the newly registered member")
 * )
 * 
 * @OA\Schema(
 *     schema="Member",
 *     required={},
 *     @OA\Property(property="id", type="integer", example=81559379910656),
 *     @OA\Property(property="unique_identifier", type="string", example="700-857-223-945", description="Unique identifier for the member"),
 *     @OA\Property(property="name", type="string", maxLength=64, example="Member Name"),
 *     @OA\Property(property="email", type="string", format="email", example="member@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Timestamp when the email was verified"),
 *     @OA\Property(property="locale", type="string", minLength=5, maxLength=12, nullable=true, example="en_US", description="Locale of the member"),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, nullable=true, example="USD", description="Preferred currency of the member"),
 *     @OA\Property(property="time_zone", type="string", nullable=true, example="America/New_York", description="Time zone of the member"),
 *     @OA\Property(property="accepts_emails", type="integer", enum={0, 1}, example=0, nullable=true, description="Indicates if the member accepts emails"),
 *     @OA\Property(property="number_of_times_logged_in", type="integer", example=6, description="Number of times the member has logged in"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last login timestamp of the member"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-06-20T09:12:01.000000Z", description="Creation timestamp of the member record"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T13:20:57.000000Z", description="Last update timestamp of the member record"),
 *     @OA\Property(property="avatar", type="string", nullable=true, description="Avatar URL of the member")
 * )
 * 
 * @OA\Schema(
 *     schema="Club",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=81559380115456, description="Unique identifier for the club"),
 *     @OA\Property(property="name", type="string", maxLength=120, example="Club name", description="Name of the club"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of the club record"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of the club record")
 * )
 * 
 * @OA\Schema(
 *     schema="Card",
 *     required={
 *         "id", "club_id", "name", "head", "title", "description", "unique_identifier",
 *         "issue_date", "expiration_date", "bg_color", "bg_color_opacity", "text_color",
 *         "text_label_color", "qr_color_light", "qr_color_dark", "currency",
 *         "initial_bonus_points", "points_expiration_months", "currency_unit_amount",
 *         "points_per_currency", "min_points_per_purchase", "max_points_per_purchase",
 *         "is_visible_by_default", "is_visible_when_logged_in",
 *         "total_amount_purchased", "number_of_points_issued", "last_points_issued_at",
 *         "number_of_points_redeemed", "number_of_rewards_redeemed", "last_reward_redeemed_at",
 *         "views", "last_view", "created_by", "created_at", "updated_at"
 *     },
 *     @OA\Property(property="id", type="integer", example=81559402807296, description="Unique identifier for the Card"),
 *     @OA\Property(property="club_id", type="integer", example=81559380115456, description="Unique identifier for the Club associated with the Card"),
 *     @OA\Property(property="name", type="string", description="Name of the Card"),
 *     @OA\Property(
 *         property="head",
 *         type="object",
 *         description="Multilingual card name",
 *         @OA\Property(property="de_DE", type="string", example="Gesunde Ernährung"),
 *         @OA\Property(property="en_US", type="string", example="Healthy Eats"),
 *         @OA\Property(property="es_ES", type="string", example="Comida Saludable"),
 *         @OA\Property(property="fr_FR", type="string", example="Repas Sains"),
 *         @OA\Property(property="nl_NL", type="string", example="Gezond Eten"),
 *         @OA\Property(property="pt_BR", type="string", example="Comidas Saudáveis")
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="object",
 *         description="Multilingual card title",
 *         @OA\Property(property="de_DE", type="string", example="Salat Ersparnisse"),
 *         @OA\Property(property="en_US", type="string", example="Salad Savings"),
 *         @OA\Property(property="es_ES", type="string", example="Ahorros en Ensaladas"),
 *         @OA\Property(property="fr_FR", type="string", example="Économies sur les Salades"),
 *         @OA\Property(property="nl_NL", type="string", example="Salade Besparingen"),
 *         @OA\Property(property="pt_BR", type="string", example="Economia em Salada")
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="object",
 *         description="Multilingual card description",
 *         @OA\Property(property="de_DE", type="string", example="Belohnung für gesunde Entscheidungen!"),
 *         @OA\Property(property="en_US", type="string", example="Get rewarded for healthy choices!"),
 *         @OA\Property(property="es_ES", type="string", example="¡Obtén recompensas por elecciones saludables!"),
 *         @OA\Property(property="fr_FR", type="string", example="Soignez vos choix et soyez récompensé(e) !"),
 *         @OA\Property(property="nl_NL", type="string", example="Word beloond voor gezonde keuzes!"),
 *         @OA\Property(property="pt_BR", type="string", example="Seja recompensado por escolhas saudáveis!")
 *     ),
 *     @OA\Property(property="unique_identifier", type="string", description="Unique identifier of the Card"),
 *     @OA\Property(property="issue_date", type="string", format="date-time", description="Issue date of the Card"),
 *     @OA\Property(property="expiration_date", type="string", format="date-time", description="Expiration date of the Card"),
 *     @OA\Property(property="bg_color", type="string", example="#CCCCCC", description="Background color of the Card"),
 *     @OA\Property(property="bg_color_opacity", type="integer", example=80, description="Background color opacity of the Card"),
 *     @OA\Property(property="text_color", type="string", example="#333333", description="Text color of the Card"),
 *     @OA\Property(property="text_label_color", type="string", example="#333333", description="Text label color of the Card"),
 *     @OA\Property(property="qr_color_light", type="string", example="#CCCCCC", description="QR code light color of the Card"),
 *     @OA\Property(property="qr_color_dark", type="string", example="#222222", description="QR code dark color of the Card"),
 *     @OA\Property(property="currency", type="string", example="USD", description="Currency of the Card"),
 *     @OA\Property(property="initial_bonus_points", type="integer", description="Initial bonus points of the Card"),
 *     @OA\Property(property="points_expiration_months", type="integer", description="Number of months after which the points on the Card expire"),
 *     @OA\Property(property="currency_unit_amount", type="integer", description="Currency unit amount of the Card"),
 *     @OA\Property(property="points_per_currency", type="integer", description="Number of points earned per currency unit spent using the Card"),
 *     @OA\Property(property="min_points_per_purchase", type="integer", description="Minimum points that can be earned per purchase using the Card"),
 *     @OA\Property(property="max_points_per_purchase", type="integer", description="Maximum points that can be earned per purchase using the Card"),
 *     @OA\Property(property="is_visible_by_default", type="integer", description="Indicates if the Card is visible by default (1 for true, 0 for false)"),
 *     @OA\Property(property="is_visible_when_logged_in", type="integer", description="Indicates if the Card is visible when a user is logged in (1 for true, 0 for false)"),
 *     @OA\Property(property="total_amount_purchased", type="integer", description="Total amount purchased using the Card"),
 *     @OA\Property(property="number_of_points_issued", type="integer", description="Total number of points issued for the Card"),
 *     @OA\Property(property="last_points_issued_at", type="string", format="date-time", description="Last time points were issued for the Card"),
 *     @OA\Property(property="number_of_points_redeemed", type="integer", description="Total number of points redeemed from the Card"),
 *     @OA\Property(property="number_of_rewards_redeemed", type="integer", description="Total number of rewards redeemed using the Card"),
 *     @OA\Property(property="last_reward_redeemed_at", type="string", format="date-time", description="Last time a reward was redeemed using the Card"),
 *     @OA\Property(property="views", type="integer", description="Number of times the Card was viewed"),
 *     @OA\Property(property="last_view", type="string", format="date-time", description="Last time the Card was viewed"),
 *     @OA\Property(property="meta", type="object", nullable=true, description="Additional metadata associated with the Card in JSON format"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of the Card"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of the Card"),
 *     @OA\Property(property="balance", type="integer", example=-1, description="The member's balance. This field has a value of 0 or higher for API calls where a member is authenticated. If the balance is not applicable or unavailable, its value is -1.")
 * )
 * 
 * @OA\Schema(
 *     schema="StaffMember",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=81559436075008, description="The unique identifier of the staff member"),
 *     @OA\Property(property="club_id", type="integer", example=81559380996096, description="The unique identifier of the club associated with the staff member"),
 *     @OA\Property(property="name", type="string", example="John Doe", description="The name of the staff member"),
 *     @OA\Property(property="email", type="string", format="email", example="johndoe@example.com", description="The email address of the staff member"),
 *     @OA\Property(property="time_zone", type="string", example="America/New_York", description="The time zone of the staff member"),
 *     @OA\Property(property="number_of_times_logged_in", type="integer", example=5, description="The number of times the staff member has logged in"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2023-01-01T00:00:00Z", description="The timestamp of the staff member's last login"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z", description="The timestamp of when the staff member was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z", description="The timestamp of the last update of the staff member's details"),
 *     @OA\Property(property="avatar", type="string", format="uri", example="https://example.com/files/31/icon-80x80-v2.png", description="The URL to the avatar image of the staff member")
 * )
 * 
 * The schema for a purchase request.
 *
 * @OA\Schema(
 *     schema="PurchaseRequest",
 *     type="object",
 *     required={"purchase_amount"},
 *     @OA\Property(property="purchase_amount", type="number", description="The amount of money that was spent on the purchase."),
 *     @OA\Property(property="note", type="string", description="An optional note that can be added to the purchase.", nullable=true)
 * )
 * 
 * The schema for a transaction.
 *
 * @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     @OA\Property(property="staff_id", type="integer", example=81559460237312, description="The unique identifier of the staff member who created the transaction"),
 *     @OA\Property(property="member_id", type="integer", example=81559379910656, description="The unique identifier of the member who made the purchase"),
 *     @OA\Property(property="card_id", type="integer", example=81559379480576, description="The unique identifier of the card that was used for the purchase"),
 *     @OA\Property(property="partner_name", type="string", example="Partner Name", description="The name of the partner"),
 *     @OA\Property(property="partner_email", type="string", example="partner@example.com", description="The email of the partner"),
 *     @OA\Property(property="purchase_amount", type="number", description="The amount of money that was spent on the purchase"),
 *     @OA\Property(property="note", type="string", description="An optional note that can be added to the purchase", nullable=true),
 *     @OA\Property(property="points_issued", type="integer", format="int64", description="The number of points issued for the purchase"),
 *     @OA\Property(property="reward_redeemed", type="boolean", description="Indicates whether a reward was redeemed for the purchase"),
 *     @OA\Property(property="reward_id", type="integer", example=81559379689472, description="The unique identifier of the reward that was redeemed"),
 *     @OA\Property(property="transaction_date", type="string", format="date-time", description="The timestamp of the transaction"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp of when the transaction was created")
 * )
 */

class OpenApiSchemas
{
}
