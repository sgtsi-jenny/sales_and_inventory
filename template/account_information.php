<div class='row'>
                        <div class='panel panel-primary'>
                        <div class='panel-heading text-center'>
                            <h4>
                            <b>Client Information</b>
                            </h4>
                        </div>
                        <table class='table table-bordered table-condensed'>
                            <tr>
                                <th>Client name:</th>
                                <td><?php echo htmlspecialchars($org['org_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Handler:</th>
                                <td><?php echo htmlspecialchars($org['users']) ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?php echo htmlspecialchars($org['phone_num']) ?></td>
                            </tr>
                            <tr>
                                <th>Email Address:</th>
                                <td><?php echo htmlspecialchars($org['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Industry:</th>
                                <td><?php echo htmlspecialchars($org['industry']) ?></td>
                            </tr>
                            <tr>
                                <th>Ratings:</th>
                                <td><?php echo htmlspecialchars($org['rating']) ?></td>
                            </tr>
                            <tr>
                                <th>Annual Revenue:</th>
                                <td><?php echo htmlspecialchars($org['annual_revenue']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Created:</th>
                                <td><?php echo htmlspecialchars($org['date_created']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Modified:</th>
                                <td><?php echo htmlspecialchars($org['date_modified']) ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?php echo htmlspecialchars($org['description']) ?></td>
                            </tr>
                        </table>
                        </div>
                    </div>