<?php

namespace Drupal\search_api_solr;

use Drupal\search_api\Backend\BackendInterface;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Query\QueryInterface;
use Solarium\QueryType\Update\Query\Query as UpdateQuery;

/**
 * Defines an interface for Solr search backend plugins.
 *
 * It extends the generic \Drupal\search_api\Backend\BackendInterface and covers
 * additional Solr specific methods.
 */
interface SolrBackendInterface extends BackendInterface {

  /**
   * Creates a list of all indexed field names mapped to their Solr field names.
   *
   * The special fields "search_api_id" and "search_api_relevance" are also
   * included. Any Solr fields that exist on search results are mapped back to
   * to their local field names in the final result set.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   The Search Api index.
   * @param bool $reset
   *   (optional) Whether to reset the static cache.
   *
   * @see SearchApiSolrBackend::search()
   */
  public function getSolrFieldNames(IndexInterface $index, $reset = FALSE);

  /**
   * Returns the Solr connector used for this backend.
   *
   * @return \Drupal\search_api_solr\SolrConnectorInterface
   *
   * @throws \Drupal\search_api\SearchApiException
   */
  public function getSolrConnector();

  /**
   * Retrieves a Solr document from an search api index item.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   The search api index.
   * @param \Drupal\search_api\Item\ItemInterface $item
   *   An item to get documents for.
   *
   * @return \Solarium\QueryType\Update\Query\Document\Document
   *   A solr document.
   */
  public function getDocument(IndexInterface $index, ItemInterface $item);

  /**
   * Retrieves Solr documents from search api index items.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   The search api index.
   * @param \Drupal\search_api\Item\ItemInterface[] $items
   *   An array of items to get documents for.
   * @param \Solarium\QueryType\Update\Query\Query $update_query
   *   The existing update query the documents should be added to.
   *
   * @return \Solarium\QueryType\Update\Query\Document\Document[]
   *   An array of solr documents.
   */
  public function getDocuments(IndexInterface $index, array $items, UpdateQuery $update_query = NULL);

  /**
   * Extract a file's content using tika within a solr server.
   *
   * @param string $filepath
   *   The real path of the file to be extracted.
   *
   * @return string
   *   The text extracted from the file.
   */
  public function extractContentFromFile($filepath);

  /**
   * Returns the targeted content domain of the server.
   *
   * @return string
   */
  public function getDomain();

  /**
   * Indicates if the Solr server uses a managed schema.
   *
   * @return bool
   *   True if the Solr server uses a managed schema, false if the Solr server
   *   uses a classic schema.
   */
  public function isManagedSchema();

  /**
   * Returns a ready to use query string to filter results by index and site.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *
   * @return string
   */
  public function getIndexFilterQueryString(IndexInterface $index);

  /**
   * Prefixes an index ID as configured.
   *
   * The resulting ID will be a concatenation of the following strings:
   * - If set, the server-specific index_prefix.
   * - If set, the index-specific prefix.
   * - The index's machine name.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   The index.
   *
   * @return string
   *   The prefixed machine name.
   */
  public function getIndexId(IndexInterface $index);

  /**
   * Returns the targeted Index ID. In case of multisite it might differ.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *
   * @return string
   */
  public function getTargetedIndexId(IndexInterface $index);

  /**
   * Returns the targeted site hash. In case of multisite it might differ.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *
   * @return string
   */
  public function getTargetedSiteHash(IndexInterface $index);

  /**
   * Executes a streaming expression.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *
   * @return \Solarium\QueryType\Stream\Result
   *
   * @throws \Drupal\search_api\SearchApiException
   * @throws \Drupal\search_api_solr\SearchApiSolrException
   */
  public function executeStreamingExpression(QueryInterface $query);

  /**
   * Executes a graph streaming expression.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *
   * @return \Solarium\QueryType\Graph\Result
   *
   * @throws \Drupal\search_api\SearchApiException
   * @throws \Drupal\search_api_solr\SearchApiSolrException
   */
  public function executeGraphStreamingExpression(QueryInterface $query);

  /**
   * Apply any finalization commands to a solr index.
   *
   * Only if globally configured to do so and only the first time after changes
   * to the index from the drupal side.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *
   * @throws \Drupal\search_api_solr\SearchApiSolrException
   */
  public function finalizeIndex(IndexInterface $index);

  /**
   * Gets schema language statistics for the multilingual Solr server.
   *
   * @return array
   *   Stats as associative array keyed by language IDs and a boolean value to
   *   indicate if corresponding field types are existing on the server's
   *   current schema.
   */
  public function getSchemaLanguageStatistics();

}
